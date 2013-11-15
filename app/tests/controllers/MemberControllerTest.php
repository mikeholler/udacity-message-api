<?php

use \Mockery as m;

class MemberControllerTest extends TestCase {

    /**
     * @var m\Mock
     */
    protected $memberModel;

    public function setUp() {
        parent::setUp();

        $this->memberModel = $this->mock('MemberModel');
    }

    public function testIndex() {
        $data = ['data'];
        $this->memberModel->shouldReceive('getAll')->once()
            ->andReturn($data);

        $response = $this->call('GET', 'groups/1/members');

        $this->assertResponseOk();
        $this->assertEquals(json_encode($data), $response->getContent());
    }

    public function testUpdate() {
        $this->memberModel->shouldReceive('add')->once()
            ->with(1, 2)->andReturn(null);

        $this->call('PUT', 'groups/2/members/1');

        $this->assertResponseOk();
    }

    public function testDestroy() {
        $this->memberModel->shouldReceive('delete')->once()
            ->with(1, 2);

        $this->call('DELETE', 'groups/2/members/1');
    }

}