<?php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Perform operations on users stored in the database.
 */
class UserModel extends BaseModel {

    const TABLE = 'user';
    const USER_ID = 'userId';
    const USERNAME = 'username';
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
     * Get a list of all users.
     *
     * @param int|null $limit
     * @param int|null $offset
     * @return array Array of user objects.
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
     * Get all groups a user belongs to.
     *
     * @param int $userId
     */
    public function getGroups($userId)
    {
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
     * Get an individual user.
     *
     * @param int $userId
     *
     * @return stdClass
     *
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getOne($userId)
    {
        $user = $this->table->where(self::USER_ID, $userId)->first();

        if (!$user)
        {
            throw new NotFoundHttpException;
        }

        return $user;
    }

    /**
     * Create a user.
     *
     * @param string $username
     *
     * @return int userId
     *
     * @throws Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function create($username)
    {

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

    /**
     * Delete a user.
     *
     * @param int $userId
     */
    public function delete($userId) {
        $this->table->where(self::USER_ID, $userId)->delete();
    }

}