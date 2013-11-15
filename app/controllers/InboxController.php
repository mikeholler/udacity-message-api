<?php

class InboxController extends \BaseController {

    protected $messageModel;

    public function __construct(MessageModel $messageModel) {
        $this->messageModel = $messageModel;
    }

	/**
	 * Display a listing of the resource.
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
	 * Display the specified resource.
	 *
	 * @param  int  $userId
     * @param  int  $messageId
     *
	 * @return \Illuminate\Http\Response
	 */
	public function show($userId, $messageId)
	{
        return Response::json($this->messageModel->getMessage($userId, $messageId));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $userId
     * @param  int  $messageId
	 */
	public function destroy($userId, $messageId)
	{
        $this->messageModel->deleteMessage($userId, $messageId);
	}

}