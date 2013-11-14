<?php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
                ->first();

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
     * Send a message as the given user.
     *
     * @param int $userId
     * @param SendMessageStruct $message
     */
    public function sendMessage($userId, SendMessageStruct $message) {
        DB::transaction(function () use ($userId, $message) {

            // Create the outbox entry and message body. There is only
            // one of each for each message.
            $messageId = $this->createSenderMessage($userId, $message);
            $this->createMessageBody($messageId, $message->body);


            if ($message->broadcast)
            {
                $this->deliverBroadcastMessage($messageId, $userId);
            }
            else if ($message->toGroups)
            {
                $this->deliverGroupMessage($messageId, $message->toGroups);
//                $this->deliverMessage($messageId, $message->toUsers, $message->toGroups);
            }
            else
            {
                $this->deliverUserMessage($messageId, $message->toUsers);
            }
        });
    }

    /**
     * Create the "outbox" message entry for the sender.
     *
     * @param int $userId
     * @param SendMessageStruct $message
     *
     * @return int messageId
     */
    protected function createSenderMessage($userId, SendMessageStruct $message) {
        return $this->tableMessageSend->insertGetId(
            [
                self::ATTR_FROM_USER => $userId,
                self::ATTR_SUBJECT => $message->subject,
                self::ATTR_CREATED => TimeHelper::formattedUtcDatetime()
            ]
        );
    }

    /**
     * Store the message body in one place.
     *
     * @param int $messageId
     * @param string $body
     */
    protected function createMessageBody($messageId, $body) {
        $this->tableMessageBody->insert(
            [
                self::ATTR_MESSAGE_ID => $messageId,
                self::ATTR_BODY => $body
            ]
        );
    }

    protected function getAffectedRows() {
        return DB::select('SELECT ROW_COUNT() as count')[0]->count;
    }

    /**
     * Put an entry in every users' inbox (except the sender's) for a particular message.
     *
     * @param int $messageId
     * @param int $fromUserId
     *
     * @throws BadRequestHttpException
     */
    protected function deliverBroadcastMessage($messageId, $fromUserId) {
        $columns = [self::ATTR_MESSAGE_ID, self::ATTR_TO_USER, self::ATTR_READ];

        $sql = "
            INSERT INTO messageReceive (messageId, toUser, `read`)
                SELECT ?, userId, 0
                    FROM `user`
                    WHERE userId != ?
        ";

        DB::statement($sql, [$messageId, $fromUserId]);

        if (!$this->getAffectedRows())
        {
            throw new BadRequestHttpException('Nobody can receive this message.');
        }
    }

    /**
     * Send a message to all members of the given groups.
     *
     * @param int $messageId
     * @param array $groupNames
     *
     * @throws BadRequestHttpException
     */
    protected function deliverGroupMessage($messageId, array $groupNames) {
        $groupPlaceholders = implode(',', array_pad(array(), count($groupNames), '?'));
        $values = array_merge([$messageId], $groupNames);

        $sql = "
            INSERT INTO messageReceive (messageId, toUser, `read`)
                SELECT ?, userId, 0
                    FROM (SELECT m.userId
                               FROM `group` g
                               JOIN `groupMember` m ON g.groupId = m.groupId
                               WHERE g.groupName IN ($groupPlaceholders)) AS userIds;
        ";

        DB::statement($sql, $values);

        if (!$this->getAffectedRows())
        {
            throw new BadRequestHttpException('Nobody can receive this message.');
        }
    }

    /**
     * Send a message to all given users.
     *
     * @param int $messageId
     * @param array $usernames
     *
     * @throws BadRequestHttpException
     */
    protected function deliverUserMessage($messageId, array $usernames) {
        $userPlaceholders = implode(',', array_pad(array(), count($usernames), '?'));
        $values = array_merge([$messageId], $usernames);

        $sql = "
            INSERT INTO messageReceive (messageId, toUser, `read`)
                SELECT ?, userId, 0
                    FROM (SELECT userId
                               FROM `user`
                               WHERE username IN ($userPlaceholders)) AS userIds;
        ";

        DB::statement($sql, $values);

        if (!$this->getAffectedRows())
        {
            throw new BadRequestHttpException('Nobody can receive this message.');
        }
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
        $this->tableMessageReceive
            ->where(self::ATTR_TO_USER, $userId)
            ->where(self::ATTR_MESSAGE_ID, $messageId)
            ->update([self::ATTR_READ => true]);
    }

}