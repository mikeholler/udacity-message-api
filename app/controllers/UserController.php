<?php

use \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserController extends \BaseController {

    protected $userModel;

    public function __construct() {
        $this->userModel = new UserModel;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return Response::json($this->userModel->getAll());
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

}