<?php

/**
 * Handle messages received by a given user.
 */
class InboxController extends \Controller {

    /**
     * @var MessageModel
     */
    protected $messageModel;

    public function __construct(MessageModel $messageModel)
    {
        $this->messageModel = $messageModel;
    }

	/**
	 * Get a list of messages in a given user's inbox.
     *
     * Supports limit and offset url parameters for pagination.
	 *
     * @param int $userId
     *
	 * @return \Illuminate\Http\Response
	 */
	public function index($userId)
	{
        $limit = Input::get('limit');
        $offset = Input::get('offset');

        $limit = is_numeric($limit) or is_null($limit) ? intval($limit) : null;
        $offset = is_numeric($offset) or is_null($offset) ? intval($offset) : null;

        return Response::json($this->messageModel->getInbox($userId, $limit, $offset));
	}

	/**
	 * Open a message in the user's inbox.
     *
     * Messages are automatically marked as read when opened.
	 *
	 * @param int $userId
     * @param int $messageId
     *
	 * @return \Illuminate\Http\Response
	 */
	public function show($userId, $messageId)
	{
        return Response::json($this->messageModel->getMessage($userId, $messageId));
	}

	/**
     * Delete message from user's inbox.
	 *
	 * @param int $userId
     * @param int $messageId
	 */
	public function destroy($userId, $messageId)
	{
        $this->messageModel->deleteMessage($userId, $messageId);
	}

}