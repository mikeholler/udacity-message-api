<?php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Message inbox.
 *
 * Unlike the other models, InboxModel is not directly tied to a single table.
 * To reduce data redundancy and increase efficiency, an inbox is made up of
 * elements from the TABLE_MESSAGE_SEND, TABLE_MESSAGE_BODY, and
 * TABLE_MESSAGE_RECEIVE tables.
 */
class MessageModel extends BaseModel {

    /**
     * Name of database table that contains all sender-specific meta data
     * about a message (sender, sent time, etc.).
     */
    const TABLE_MESSAGE_SEND = 'messageSend';

    /**
     * Name of database table that contains the body of each message. This
     * is not included in the TABLE_MESSAGE table because messages can potentially
     * be very large. Including message body in TABLE_MESSAGES would tightly couple
     * meta data (TABLE_MESSAGE) with regular data (TABLE_MESSAGE_BODY) and slow inbox
     * listing queries.
     */
    const TABLE_MESSAGE_BODY = 'messageBody';

    /**
     * Name of database table that contains all recipient-specific meta data
     * about a message, including whether they've read or deleted a given message.
     */
    const TABLE_MESSAGE_RECEIVE = 'messageReceive';

    /**
     * Attributes from TABLE_MESSAGE_SEND.
     */
    const ATTR_MESSAGE_ID = 'messageId';
    const ATTR_FROM_USER = 'fromUser';
    const ATTR_SUBJECT = 'subject';
    const ATTR_CREATED = 'created';

    /**
     * Attributes from TABLE_MESSAGE_BODY.
     */
    const ATTR_BODY = 'body';

    /**
     * Attributes from TABLE_MESSAGE_RECEIVE.
     */
    const ATTR_TO_USER = 'toUser';
    const ATTR_READ = 'read';

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    protected $tableMessageSend;

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    protected $tableMessageBody;

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    protected $tableMessageReceive;

    public function __construct() {
        $this->tableMessageSend = DB::table(self::TABLE_MESSAGE_SEND);
        $this->tableMessageBody= DB::table(self::TABLE_MESSAGE_BODY);
        $this->tableMessageReceive = DB::table(self::TABLE_MESSAGE_RECEIVE);
    }

    /**
     * Get an inbox listing of all messages a user has
     *
     * @param int $userId
     * @param int $limit
     * @param int $offset
     *
     * @return array|static
     */
    public function getInbox($userId, $limit=null, $offset=null) {
        $query = $this->tableMessageSend
            ->join(
                self::TABLE_MESSAGE_RECEIVE,
                self::qualify([self::TABLE_MESSAGE_SEND, self::ATTR_MESSAGE_ID]),
                '=',
                self::qualify([self::TABLE_MESSAGE_RECEIVE, self::ATTR_MESSAGE_ID]))
            ->where(self::qualify([self::TABLE_MESSAGE_RECEIVE, self::ATTR_TO_USER]), $userId);

        // Allow for pagination.
        if ($limit) {
            $query = $query->limit($limit);
            if ($offset) $query = $query->offset($offset);
        }

        return $query->get();
    }

    /**
     * Get a message from the user's inbox.
     *
     * When a user gets a message, the server automatically marks it as read.
     *
     * @param int $userId
     * @param int $messageId
     *
     * @throws NotFoundHttpException
     */
    public function getMessage($userId, $messageId) {

        return DB::transaction(function() use ($userId, $messageId){
            $this->markRead($userId, $messageId);
            $messages = $this->tableMessageSend
                ->join(
                    self::TABLE_MESSAGE_RECEIVE,
                    self::qualify([self::TABLE_MESSAGE_SEND, self::ATTR_MESSAGE_ID]),
                    '=',
                    self::qualify([self::TABLE_MESSAGE_RECEIVE, self::ATTR_MESSAGE_ID]))
                ->join(
                    self::TABLE_MESSAGE_BODY,
                    self::qualify([self::TABLE_MESSAGE_SEND, self::ATTR_MESSAGE_ID]),
                    '=',
                    self::qualify([self::TABLE_MESSAGE_BODY, self::ATTR_MESSAGE_ID]))
                ->where(self::qualify([self::TABLE_MESSAGE_RECEIVE, self::ATTR_MESSAGE_ID]), $messageId)
                ->where(self::qualify([self::TABLE_MESSAGE_RECEIVE, self::ATTR_TO_USER]), $userId)
                ->get();

            if (!$messages) throw new NotFoundHttpException;

            return $messages;
        });
    }

    /**
     * Delete a message from a user's inbox.
     *
     * Note, the message content never gets deleted, because it is still
     * in the sender's sent items.
     *
     * @param int $userId
     * @param int $messageId
     */
    public function deleteMessage($userId, $messageId) {
        $this->tableMessageReceive
            ->where(self::ATTR_TO_USER, $userId)
            ->where(self::ATTR_MESSAGE_ID, $messageId)
            ->delete();
    }

    /**
     * Mark a message as read by a particular user.
     *
     * @param int $userId
     * @param int $messageId
     *
     * @throws NotFoundHttpException
     */
    protected function markRead($userId, $messageId) {
        $rowsModified = $this->tableMessageReceive
            ->where(self::ATTR_TO_USER, $userId)
            ->where(self::ATTR_MESSAGE_ID, $messageId)
            ->update([self::ATTR_READ => true]);

        if (!$rowsModified) throw new NotFoundHttpException;
    }

}