<?php

use \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Audit and change information about users.
 */
class UserController extends \Controller {

    /**
     * @var UserModel
     */
    protected $userModel;

    /**
     * @var MessageModel
     */
    protected $messageModel;

    public function __construct(UserModel $userModel, MessageModel $messageModel) {
        $this->userModel = $userModel;
        $this->messageModel = $messageModel;
    }

	/**
	 * Get a list of all users.
     *
     * Supports limit and offset url parameters for pagination.
	 *
	 * @return \Illuminate\Http\Response
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
	 * Create a new user.
	 *
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
	 * Get information about a single user.
	 *
     * @param int $userId
     *
	 * @return \Illuminate\Http\Response
	 */
	public function show($userId)
	{
        return Response::json($this->userModel->getOne($userId));
	}

    /**
     * Show the groups user is member of.
     *
     * @param int $userId
     * @return \Illuminate\Http\Response
     */
    public function showGroups($userId)
    {
        return Response::json($this->userModel->getGroups($userId));
    }

	/**
	 * Delete a user.
	 *
	 * @param int $userId
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