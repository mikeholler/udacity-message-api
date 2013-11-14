<?php

use \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserController extends \BaseController {

    /**
     * @var UserModel
     */
    protected $userModel;

    /**
     * @var MessageModel
     */
    protected $messageModel;

    public function __construct() {
        $this->userModel = new UserModel;
        $this->messageModel = new MessageModel;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $limit = Input::get('limit');
        $offset = Input::get('offset');

        $limit = is_numeric($limit) or is_null($limit) ? intval($limit) : null;
        $offset = is_numeric($offset) or is_null($offset) ? intval($offset) : null;

        return Response::json($this->userModel->getAll($limit, $offset));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
     * @throws BadRequestHttpException
	 */
	public function store()
	{
        if (Input::has('username'))
            $username = Input::get('username');
        else
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

        $this->userModel->create($username);
	}

	/**
	 * Display the specified resource.
	 *
     * @param int $userId
     *
	 * @return Response
	 */
	public function show($userId)
	{
        return Response::json($this->userModel->getOne($userId));
	}

    public function showGroups($userId)
    {
        return Response::json($this->userModel->getGroups($userId));
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $userId
     *
	 * @return Response
	 */
	public function destroy($userId)
	{
        $this->userModel->delete($userId);
	}

    /**
     * Send a message as a particular user.
     *
     * @param int $userId
     */
    public function sendMessage($userId) {
        $message = new SendMessageStruct;
        $message->hydrate(Input::all());
        $message->validate();

        $this->messageModel->sendMessage($userId, $message);
    }

}