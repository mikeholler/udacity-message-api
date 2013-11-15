<?php

/**
 * Provides methods to modify groups on a user membership level.
 *
 * Get information about users in groups and modify who is in a group.
 */
class MemberController extends \Controller {

    /**
     * @var MemberModel
     */
    protected $memberModel;

    public function __construct(MemberModel $memberModel)
    {
        $this->memberModel = $memberModel;
    }

	/**
	 * List all members in group.
     *
     * Supports limit and offset url parameters for pagination.
     *
     * @param int $groupId
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($groupId)
	{
        $limit = Input::get('limit');
        $offset = Input::get('offset');

        $limit = is_numeric($limit) or is_null($limit) ? intval($limit) : null;
        $offset = is_numeric($offset) or is_null($offset) ? intval($offset) : null;

        return Response::json($this->memberModel->getAll($groupId, $limit, $offset));
	}

	/**
	 * Add user to given group.
	 *
	 * @param int $groupId
     * @param int $memberId
	 */
	public function update($groupId, $memberId)
	{
        $this->memberModel->add($memberId, $groupId);
	}

	/**
	 * Remove user from group.
	 *
	 * @param int $groupId
     * @param int $memberId
	 */
	public function destroy($groupId, $memberId)
	{
        $this->memberModel->delete($memberId, $groupId);
	}

}