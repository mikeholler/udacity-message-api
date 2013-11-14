<?php

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GroupController extends \BaseController
{

    protected $groupModel;

    public function __construct()
    {
        $this->groupModel = new GroupModel;
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

        return Response::json($this->groupModel->getAll($limit, $offset));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     * @throws BadRequestHttpException
     */
    public function store()
    {
        if (Input::has('groupName'))
            $groupName = Input::get('groupName');
        else
            throw new BadRequestHttpException('groupName missing');

        $this->groupModel->create($groupName);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        return Response::json($this->groupModel->getOne($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->groupModel->delete($id);
    }
}