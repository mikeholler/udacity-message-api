<?php

class GroupModelTest extends ModelTestCase {

    /**
     * @var GroupModel
     */
    protected $model;

    public function setUp() {
        parent::setUp();

        $this->model = new GroupModel;
    }

    public function testCreateGroup() {
        $this->model->create('foo');

        $groupname = DB::table('group')->pluck('groupName');

        $this->assertEquals('foo', $groupname);
    }

    public function testGetGroup() {
        DB::table('group')->insert(['groupId' => 1, 'groupName' => 'foo']);

        $group = $this->model->getOne(1);

        $this->assertEquals('foo', $group->groupName);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetGroupNotExists() {
        $this->model->getOne(1);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function testCreateDuplicateGroup() {
        $this->model->create('foo');
        $this->model->create('foo');
    }

    public function testDeleteGroup() {
        DB::table('group')->insert(['groupId' => 1, 'groupName' => 'foo']);

        $this->model->delete(1);

        $result = DB::table('group')->first();

        $this->assertNull($result);
    }

}