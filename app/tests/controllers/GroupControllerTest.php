<?php

use \Mockery as m;

class GroupControllerTest extends TestCase {

    /**
     * @var m\Mock
     */
    protected $groupModel;

    public function setUp() {
        parent::setUp();

        $this->groupModel = $this->mock('GroupModel');
    }

    public function tearDown() {
        m::close();
        parent::tearDown();
    }

    public function testIndex() {
        $data = ['data'];
        $this->groupModel->shouldReceive('getAll')->once()
            ->with(1, 2)->andReturn($data);

        $response = $this->call('GET', 'groups', ['limit' => '1', 'offset' => '2']);

        $this->assertResponseOk();
        $this->assertEquals(json_encode($data), $response->getContent());
    }

    public function testStore() {
        $data = ['groupName' => 'testgroup'];
        $this->groupModel->shouldReceive('create')->once()
            ->with('testgroup');

        $this->call('POST', 'groups', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $this->assertResponseOk();
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testStoreBadRequest() {
        $this->call('POST', 'groups');
    }

    public function testShow() {
        $data = ['key' => 'value'];
        $this->groupModel->shouldReceive('getOne')
            ->with(1)->andReturn($data);

        $response = $this->call('GET', 'groups/1');

        $this->assertResponseOk();
        $this->assertEquals(json_encode($data), $response->getContent());
    }

    public function testDestroy() {
        $this->groupModel->shouldReceive('getOne')->with(1)->andReturn(1);
        $this->groupModel->shouldReceive('delete')->once()
            ->with(1);

        $this->call('DELETE', 'groups/1');

        $this->assertResponseOk();
    }

}