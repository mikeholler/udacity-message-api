<?php

class UserModelTest extends ModelTestCase {

    /**
     * @var UserModel
     */
    protected $model;

    public function setUp() {
        parent::setUp();

        $this->model = new UserModel;
    }

    public function testGetAll() {
        DB::table('user')->insert(['userId' => 1, 'username' => 'foo']);
        DB::table('user')->insert(['userId' => 2, 'username' => 'bar']);

        // Test get all
        $users = $this->model->getAll();

        $this->assertEquals(2, count($users));
        $this->assertEquals('foo', $users[0]->username);
        $this->assertEquals('bar', $users[1]->username);
    }

    public function testGetAllLimit() {
        DB::table('user')->insert(['userId' => 1, 'username' => 'foo']);
        DB::table('user')->insert(['userId' => 2, 'username' => 'bar']);

        // Test get all
        $users = $this->model->getAll(1);

        $this->assertEquals(1, count($users));
        $this->assertEquals('foo', $users[0]->username);
    }

    public function testGetAllLimitOffset() {
        DB::table('user')->insert(['userId' => 1, 'username' => 'foo']);
        DB::table('user')->insert(['userId' => 2, 'username' => 'bar']);

        // Test get all
        $users = $this->model->getAll(1, 1);

        $this->assertEquals(1, count($users));
        $this->assertEquals('bar', $users[0]->username);
    }

    public function testGetGroups() {
        DB::table('user')->insert(['userId' => 1, 'username' => 'foo']);
        DB::table('user')->insert(['userId' => 2, 'username' => 'foos']);
        DB::table('group')->insert(['groupId' => 1, 'groupName' => 'bar']);
        DB::table('group')->insert(['groupId' => 2, 'groupName' => 'baz']);
        DB::table('group')->insert(['groupId' => 3, 'groupName' => 'bazfoo']);
        DB::table('groupMember')->insert(['groupId' => 1, 'userId' => 1]);
        DB::table('groupMember')->insert(['groupId' => 2, 'userId' => 1]);
        DB::table('groupMember')->insert(['groupId' => 3, 'userId' => 2]);

        $groups = $this->model->getGroups(1);

        $this->assertEquals(2, count($groups));
        $this->assertEquals('bar', $groups[0]->groupName);
        $this->assertEquals('baz', $groups[1]->groupName);
    }

    public function testCreateUser() {
        $this->model->create('foo');

        $username = DB::table('user')->pluck('username');

        $this->assertEquals('foo', $username);
    }

    public function testGetUser() {
        DB::table('user')->insert(['userId' => 1, 'username' => 'foo']);

        $user = $this->model->getOne(1);

        $this->assertEquals('foo', $user->username);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetUserNotExists() {
        $this->model->getOne(1);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function testCreateDuplicateUser() {
        $this->model->create('foo');
        $this->model->create('foo');
    }

    public function testDeleteUser() {
        DB::table('user')->insert(['userId' => 1, 'username' => 'foo']);

        $this->model->delete(1);

        $result = DB::table('user')->first();

        $this->assertNull($result);
    }
}