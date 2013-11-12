<?php

class InboxController extends \BaseController {

    protected $indexModel;

    public function __construct() {
        $this->indexModel = new IndexModel;
    }

	/**
	 * Display a listing of the resource.
	 *
     * @param int $userId
     *
	 * @return Response
	 */
	public function index($userId)
	{
        echo "$userId";
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $userId
     * @param  int  $messageId
	 * @return Response
	 */
	public function show($userId, $messageId)
	{
        echo "$userId, $messageId";
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $userId
     * @param  int  $messageId
     *
	 * @return Response
	 */
	public function destroy($userId, $messageId)
	{
		//
	}

}