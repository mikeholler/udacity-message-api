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