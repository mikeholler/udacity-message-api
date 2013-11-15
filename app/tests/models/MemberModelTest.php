<?php

class MemberModelTest extends ModelTestCase {

    /**
     * @var MemberModel
     */
    protected $model;

    public function setUp() {
        parent::setUp();

        $this->model = new MemberModel;

        DB::table('user')->insert(['userId' => 1, 'username' => 'one']);
        DB::table('user')->insert(['userId' => 2, 'username' => 'two']);
        DB::table('user')->insert(['userId' => 3, 'username' => 'three']);

        DB::table('group')->insert(['groupId' => 1, 'groupName' => 'foo']);
        DB::table('group')->insert(['groupId' => 2, 'groupName' => 'bar']);
    }

    public function testGetAll() {
        DB::table('groupMember')->insert(['userId' => 1, 'groupId' => 1]);
        DB::table('groupMember')->insert(['userId' => 2, 'groupId' => 1]);
        DB::table('groupMember')->insert(['userId' => 3, 'groupId' => 2]);

        $groups = $this->model->getAll(1);

        $this->assertEquals(2, count($groups));
        $this->assertEquals(1, $groups[0]);
        $this->assertEquals(2, $groups[1]);
    }

    public function testGetAllLimit() {
        DB::table('groupMember')->insert(['userId' => 1, 'groupId' => 1]);
        DB::table('groupMember')->insert(['userId' => 2, 'groupId' => 1]);
        DB::table('groupMember')->insert(['userId' => 3, 'groupId' => 2]);

        $groups = $this->model->getAll(1, 1);

        $this->assertEquals(1, count($groups));
        $this->assertEquals(1, $groups[0]);
    }

    public function testGetAllLimitOffset() {
        DB::table('groupMember')->insert(['userId' => 1, 'groupId' => 1]);
        DB::table('groupMember')->insert(['userId' => 2, 'groupId' => 1]);
        DB::table('groupMember')->insert(['userId' => 3, 'groupId' => 2]);

        $groups = $this->model->getAll(1, 1, 1);

        $this->assertEquals(1, count($groups));
        $this->assertEquals(2, $groups[0]);
    }

    public function testAdd() {
        $this->model->add(1, 2);

        $member = DB::table('groupMember')->where('userId', 1)->where('groupId', 2)->first();

        $this->assertEquals(1, $member->userId);
        $this->assertEquals(2, $member->groupId);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function testAddDuplicateEntry() {
        DB::table('groupMember')->insert(['userId' => 1, 'groupId' => 1]);

        $this->model->add(1, 1);
    }

    public function testDelete() {
        DB::table('groupMember')->insert(['userId' => 1, 'groupId' => 2]);

        $this->model->delete(1, 2);

        $member = DB::table('groupMember')->where('userId', 1)->where('groupId', 2)->first();

        $this->assertNull($member);
    }

}