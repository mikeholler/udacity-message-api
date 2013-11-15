<?php

use Illuminate\Database\Query\Builder;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
     * Add a user to a particular group.
     *
     * @param int $userId
     * @param int $groupId
     *
     * @throws AccessDeniedHttpException
     */
    public function add($userId, $groupId) {
        try
        {
            $this->table->insert(
                [self::USER_ID => $userId, self::GROUP_ID => $groupId]
            );
        }
        catch (Exception $e)
        {
            throw new AccessDeniedHttpException;
        }
    }

    /**
     * Remove a user from a particular group.
     *
     * @param $userId
     * @param $groupId
     *
     * @return int
     */
    public function delete($userId, $groupId) {
        return $this->table
            ->where(self::USER_ID, $userId)
            ->where(self::GROUP_ID, $groupId)
            ->delete();
    }

}