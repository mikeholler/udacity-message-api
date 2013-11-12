<?php

class UserModel {

    const TABLE = 'user';
    const USER_ID = 'userId';
    const USERNAME = 'username';
    const CREATED = 'created';

    var $table;

    public function __construct() {
        $this->table = DB::table(UserModel::TABLE);
    }

    public function getAll() {
        return $this->table->get();
    }

    public function getOne($id) {
        return $this->table->where(self::USER_ID, $id)->first();
    }

    public function create($username) {
        return DB::table(UserModel::TABLE)->insertGetId(
            [self::USERNAME => $username, self::CREATED => TimeHelper::formattedUtcDatetime()]
        );
    }

    public function delete($id) {
        return $this->table->where(self::USER_ID, $id)->delete();
    }

}