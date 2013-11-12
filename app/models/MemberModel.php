<?php

class MemberModel {

    const TABLE = 'groupMember';
    const GROUP_ID = 'groupId';
    const USER_ID = 'userId';

    protected $table;

    public function __construct() {
        $this->table = DB::table(self::TABLE);
    }

    /**
     * Get all users in a particular group.
     *
     * @param int $groupId
     */
    public function getAll($groupId) {
        return $this->table
            ->where(self::GROUP_ID, $groupId)
            ->get([self::USER_ID]);
    }

    /**
     * Add a user to a particular group.
     *
     * @param int $groupId
     * @param int $userId
     */
    public function add($groupId, $userId) {
        return $this->table->insert(
            [self::GROUP_ID, self::USER_ID]
        );
    }

    /**
     * Remove a user from a particular group.
     *
     * @param $groupId
     * @param $userId
     */
    public function remove($groupId, $userId) {
        return $this->table
            ->where(self::GROUP_ID, $groupId)
            ->where(self::USER_ID, $userId)
            ->delete();
    }

}