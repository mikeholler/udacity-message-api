<?php

class InboxController extends \BaseController {

    protected $inboxModel;

    public function __construct(MessageModel $messageModel) {
        $this->inboxModel = $messageModel;
    }

	/**
	 * Display a listing of the resource.
	 *
     * @param int $userId
     *
	 * @return Response
	 */
	public function index($userId)
	{
        $limit = Input::get('limit');
        $offset = Input::get('offset');

        $limit = is_numeric($limit) or is_null($limit) ? intval($limit) : null;
        $offset = is_numeric($offset) or is_null($offset) ? intval($offset) : null;

        return Response::json($this->inboxModel->getInbox($userId, $limit, $offset));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $userId
     * @param  int  $messageId
	 * @return Response
	 */
	public function show($userId, $messageId)
	{
        return Response::json($this->inboxModel->getMessage($userId, $messageId));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $userId
     * @param  int  $messageId
     *
	 * @return Response
	 */
	public function destroy($userId, $messageId)
	{
        $this->inboxModel->deleteMessage($userId, $messageId);
	}

}