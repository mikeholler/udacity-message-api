<?php

use Illuminate\Database\Query\Builder;

class MemberModel extends BaseModel {

    const TABLE = 'groupMember';
    const GROUP_ID = 'groupId';
    const USER_ID = 'userId';

    /**
     * @var Builder
     */
    protected $table;

    public function __construct() {
        $this->table = DB::table(self::TABLE);
    }

    /**
     * Get all users in a particular group.
     *
     * @param int $groupId
     * @param null|int $limit
     * @param null|int $offset
     * @return array
     */
    public function getAll($groupId, $limit=null, $offset=null) {
        $query = $this->table->where(self::GROUP_ID, $groupId);

        // Allow for pagination.
        if ($limit) {
            $query = $query->limit($limit);
            if ($offset) $query = $query->offset($offset);
        }

        return $query->lists(self::USER_ID);
    }

    /**
     * Get all users in the given groups.
     *
     * @param int|array $groupNames
     *
     * @return array 1D array of all userIds in given groups.
     */
    public function getUserIdsByGroupNames($groupNames) {
        $groupNames = is_int($groupNames) ? [$groupNames] : $groupNames;

        return $this->table
            ->join(
                GroupModel::TABLE,
                self::qualify([self::TABLE, self::GROUP_ID]),
                '=',
                self::qualify([GroupModel::TABLE, GroupModel::GROUP_ID]))
            ->whereIn(
                self::qualify([GroupModel::TABLE, GroupModel::GROUP_NAME]),
                $groupNames)
            ->lists(self::qualify([self::TABLE, self::USER_ID]));
    }

    /**
     * Add a user to a particular group.
     *
     * @param int $groupId
     * @param int $userId
     */
    public function add($groupId, $userId) {
        $this->table->insert(
            [self::GROUP_ID, self::USER_ID]
        );
    }

    /**
     * Remove a user from a particular group.
     *
     * @param $groupId
     * @param $userId
     *
     * @return int
     */
    public function remove($groupId, $userId) {
        return $this->table
            ->where(self::GROUP_ID, $groupId)
            ->where(self::USER_ID, $userId)
            ->delete();
    }

}