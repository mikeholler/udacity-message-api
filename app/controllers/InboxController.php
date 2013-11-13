<?php

class InboxController extends \BaseController {

    protected $inboxModel;

    public function __construct() {
        $this->inboxModel = new MessageModel;
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
        return Response::json($this->inboxModel->getInbox($userId));
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