<?php

class GroupModel {

    const TABLE = 'group';
    const GROUP_ID = 'groupId';
    const GROUP_NAME = 'gname';
    const CREATED = 'created';

    protected $table;

    public function __construct() {
        $this->table = DB::table(self::TABLE);
    }

    public function getAll() {
        return $this->table->get();
    }

    public function getOne($id) {
        return $this->table->where(self::GROUP_ID, $id)->first();
    }

    public function create($groupName) {
        return $this->table->insertGetId([
            self::GROUP_NAME => $groupName,
            self::CREATED => TimeHelper::formattedUtcDatetime()
        ]);
    }

    public function delete($id) {
        return $this->table->where(self::GROUP_ID, $id)->delete();
    }
}