<?php

class GroupController extends \BaseController {

    protected $groupModel;

    public function __construct() {
        $this->groupModel = new GroupModel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Response::json($this->groupModel->getAll());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $groupName = Input::get('groupName');

        $this->groupModel->create($groupName);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Response::json($this->groupModel->getOne($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->groupModel->delete($id);
    }
}