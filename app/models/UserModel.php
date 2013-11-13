<?php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function getAll() {
        return $this->table->get();
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

    public function getOne($userId) {
        $user = $this->table->where(self::USER_ID, $userId)->first();

        if (!$user) throw new NotFoundHttpException;

        return $user;
    }

    public function create($username) {
        return $this->table->insertGetId([
            self::USERNAME => $username,
            self::CREATED => TimeHelper::formattedUtcDatetime()
        ]);
    }

    public function delete($id) {
        return $this->table->where(self::USER_ID, $id)->delete();
    }

}