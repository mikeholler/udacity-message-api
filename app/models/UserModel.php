<?php

class UserModel extends BaseModel {

    const TABLE = 'user';
    const USER_ID = 'userId';
    const USERNAME = 'username';
    const CREATED = 'created';

    protected $table;

    public function __construct() {
        $this->table = DB::table(self::TABLE);
    }

    public function getAll() {
        return $this->table->get();
    }

    public function getOne($id) {
        return $this->table->where(self::USER_ID, $id)->first();
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