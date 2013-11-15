<?php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides access to information in the group table.
 */
class GroupModel extends BaseModel {

    const TABLE = 'group';
    const GROUP_ID = 'groupId';
    const GROUP_NAME = 'groupName';
    const CREATED = 'created';

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    protected $table;

    public function __construct()
    {
        $this->table = DB::table(self::TABLE);
    }

    /**
     * Get all groups.
     *
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function getAll($limit=null, $offset=null)
    {
        $query = $this->table;

        // Allow for pagination.
        if ($limit)
        {
            $query = $query->limit($limit);

            if ($offset)
            {
                $query = $query->offset($offset);
            }
        }

        return $query->get();
    }

    /**
     * Get one group.
     *
     * @param int $groupId
     *
     * @return stdClass
     *
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getOne($groupId)
    {
        $group = $this->table->where(self::GROUP_ID, $groupId)->first();

        if (!$group)
        {
            throw new NotFoundHttpException;
        }

        return $group;
    }

    /**
     * Create a group.
     *
     * Group will be created with name provided.
     *
     * @param string $groupName
     *
     * @return int groupId
     *
     * @throws Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function create($groupName)
    {
        try
        {
            return $this->table->insertGetId([
                self::GROUP_NAME => $groupName,
                self::CREATED => TimeHelper::formattedUtcDatetime()
            ]);
        }
        catch (Exception $e)
        {
            throw new AccessDeniedHttpException;
        }
    }

    /**
     * Delete a group.
     *
     * @param int $groupId
     */
    public function delete($groupId) {
        $this->table->where(self::GROUP_ID, $groupId)->delete();
    }
}