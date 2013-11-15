<?php

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * A group is a collection of users. GroupController provides methods for
 * CRUD operations on group meta data. There is no user data relayed by
 * any of the methods in this controller.
 */
class GroupController extends \Controller
{

    /**
     * @var GroupModel
     */
    protected $groupModel;

    public function __construct(GroupModel $groupModel)
    {
        $this->groupModel = $groupModel;
    }

    /**
     * Get a list of groups all groups.
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

        return Response::json($this->groupModel->getAll($limit, $offset));
    }

    /**
     * Create a new group.
     *
     * @throws BadRequestHttpException
     */
    public function store()
    {
        if (Input::has('groupName'))
        {
            $groupName = Input::get('groupName');
        }
        else
        {
            throw new BadRequestHttpException('groupName missing');
        }

        $this->groupModel->create($groupName);
    }

    /**
     * Get a single group.
     *
     * @param int $groupId
     *
     * @return \Illuminate\Http\Response
     */
    public function show($groupId)
    {
        return Response::json($this->groupModel->getOne($groupId));
    }

    /**
     * Delete a group.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        $this->groupModel->delete($id);
    }
}