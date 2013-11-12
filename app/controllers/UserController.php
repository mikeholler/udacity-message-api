<?php

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
	 */
	public function store()
	{
        $username = Input::get('username');

        $this->userModel->create($username);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        return Response::json($this->userModel->getOne($id));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->userModel->delete($id);
	}

}