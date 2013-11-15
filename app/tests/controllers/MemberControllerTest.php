<?php

use \Mockery as m;

class MemberControllerTest extends TestCase {

    /**
     * @var MemberController
     */
    protected $controller;

    /**
     * @var MemberModel
     */
    protected $memberModel;

    public function setUp() {
        parent::setUp();

        $this->memberModel = m::mock('MemberModel');
        $this->controller = new MemberController($this->memberModel);
    }

    public function testIndex() {
        $this->memberModel->shouldReceive('getAll')->once()
            ->andReturn(['notEmpty']);

        $response = $this->controller->index(1);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('["notEmpty"]', $response->getContent());
    }

    public function testUpdate() {
        $this->memberModel->shouldReceive('add')->once()
            ->with(1, 2)->andReturn(null);

        $response = $this->controller->update(2, 1);

        $this->assertNull($response);
    }

    public function testDestroy() {
        $this->memberModel->shouldReceive('delete')->once()
            ->with(1, 2)->andReturn(null);

        $response = $this->controller->destroy(2, 1);

        $this->assertNull($response);
    }

}