<?php

class MemberController extends \BaseController {

    protected $memberModel;

    public function __construct() {
        $this->memberModel = new MemberModel;
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
        return Response::json($this->memberModel->getAll($groupId));
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
        $this->memberModel->add($groupId, $memberId);
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
        $this->memberModel->remove($groupId, $memberId);
	}

}