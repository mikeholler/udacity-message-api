<?php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GroupModel extends BaseModel {

    const TABLE = 'group';
    const GROUP_ID = 'groupId';
    const GROUP_NAME = 'groupName';
    const CREATED = 'created';

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

    public function getOne($id) {
        $group = $this->table->where(self::GROUP_ID, $id)->first();

        if (!$group) throw new NotFoundHttpException;

        return $group;
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