<?php

class MemberController extends \BaseController {

    protected $memberModel;

    public function __construct(MemberModel $memberModel) {
        $this->memberModel = $memberModel;
    }

	/**
	 * List all members in a given group.
     *
     * @param int $groupId
	 *
	 * @return Response
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
	 * Add user to a given group.
	 *
	 * @param int $groupId
     * @param int $memberId
     *
	 * @return Response
	 */
	public function update($groupId, $memberId)
	{
        $this->memberModel->add($memberId, $groupId);
	}

	/**
	 * Remove user from given group.
	 *
	 * @param  int  $groupId
     * @param int $memberId
     *
	 * @return Response
	 */
	public function destroy($groupId, $memberId)
	{
        $this->memberModel->delete($memberId, $groupId);
	}

}