<?php

use \Mockery as m;

class MemberControllerTest extends TestCase {

    /**
     * @var m\Mock
     */
    protected $memberModel;

    /**
     * @var m\Mock
     */
    protected $userModel;

    /**
     * @var m\Mock
     */
    protected $groupModel;

    public function setUp() {
        parent::setUp();

        $this->memberModel = $this->mock('MemberModel');

        // Need to mock this for model binding.
        $this->userModel = $this->mock('UserModel');
        $this->groupModel = $this->mock('GroupModel');
    }

    public function testIndex() {
        $data = ['data'];
        $this->groupModel->shouldReceive('getOne')->with(1)->andReturn(1);
        $this->memberModel->shouldReceive('getAll')->once()
            ->andReturn($data);

        $response = $this->call('GET', 'groups/1/members');

        $this->assertResponseOk();
        $this->assertEquals(json_encode($data), $response->getContent());
    }

    public function testUpdate() {
        $this->groupModel->shouldReceive('getOne')->with(2)->andReturn(2);
        $this->userModel->shouldReceive('getOne')->andReturn(1);

        $this->memberModel->shouldReceive('add')->once()
            ->with(1, 2)->andReturn(null);

        $this->call('PUT', 'groups/2/members/1');

        $this->assertResponseOk();
    }

    public function testDestroy() {
        $this->groupModel->shouldReceive('getOne')->with(2)->andReturn(2);
        $this->userModel->shouldReceive('getOne')->andReturn(1);

        $this->memberModel->shouldReceive('delete')->once()
            ->with(1, 2);

        $this->call('DELETE', 'groups/2/members/1');
    }

}