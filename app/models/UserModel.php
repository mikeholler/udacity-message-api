<?php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserModel extends BaseModel {

    const TABLE = 'user';
    const USER_ID = 'userId';
    const USERNAME = 'username';
    const CREATED = 'created';

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    protected $table;

    public function __construct() {
        $this->table = DB::table(self::TABLE);
    }

    public function getAll($limit=null, $offset=null) {
        $query = $this->table;

        // Allow for pagination.
        if ($limit) {
            $query = $query->limit($limit);
            if ($offset) $query = $query->offset($offset);
        }

        return $query->get();
    }

    /**
     * Get all groups a user belongs to.
     *
     * @param int $userId
     */
    public function getGroups($userId) {
        return DB::table(MemberModel::TABLE)
            ->join(
                GroupModel::TABLE,
                self::qualify([MemberModel::TABLE, GroupModel::GROUP_ID]),
                '=',
                self::qualify([GroupModel::TABLE, GroupModel::GROUP_ID]))
            ->where(self::qualify([MemberModel::TABLE, MemberModel::USER_ID]), $userId)
            ->get([
                self::qualify([MemberModel::TABLE, MemberModel::GROUP_ID]),
                self::qualify([GroupModel::TABLE, GroupModel::GROUP_NAME])
            ]);
    }

    /**
     * Bulk convert usernames to userIds.
     *
     * @param int|array $usernames
     *
     * @return array 1D array of userIds.
     */
    public function getUserIdsByUsername($usernames) {
        $usernames = is_int($usernames) ? [$usernames] : $usernames;

        return $this->table
            ->whereIn(self::USERNAME, $usernames)->lists(self::USER_ID);
    }

    /**
     * Get all userIds.
     *
     * @return array list of userIds
     */
    public function getUserIds() {
        return $this->table->lists(self::USER_ID);
    }

    public function getOne($userId) {
        $user = $this->table->where(self::USER_ID, $userId)->first();

        if (!$user) throw new NotFoundHttpException;

        return $user;
    }

    public function create($username) {

        try
        {
            return $this->table->insertGetId([
                self::USERNAME => $username,
                self::CREATED => TimeHelper::formattedUtcDatetime()
            ]);
        }
        catch (Exception $e)
        {
            throw new AccessDeniedHttpException;
        }
    }

    public function delete($id) {
        return $this->table->where(self::USER_ID, $id)->delete();
    }

}