<?php

use \Mockery as m;

class GroupControllerTest extends TestCase {

    /**
     * @var GroupController
     */
    protected $controller;

    /**
     * @var GroupModel
     */
    protected $groupModel;

    public function setUp() {
        parent::setUp();

        $this->groupModel = m::mock('GroupModel');
        $this->controller = new GroupController($this->groupModel);
    }

    public function tearDown() {
        m::close();
        parent::tearDown();
    }

    public function testIndex() {
        $this->groupModel->shouldReceive('getAll')->once()
            ->andReturn(['notEmpty']);

        $response = $this->controller->index();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('["notEmpty"]', $response->getContent());
    }

//    public function testStore() {
//
//    }
//
//    /**
//     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
//     */
//    public function testStoreBadRequest() {
//
//    }

    public function testShow() {
        $this->groupModel->shouldReceive('getOne')->once()
            ->with(1)->andReturn(['notEmpty']);

        $response = $this->controller->show(1);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('["notEmpty"]', $response->getContent());
    }

    public function testDestroy() {
        $this->groupModel->shouldReceive('delete')->once()
            ->with(1)->andReturn(null);

        $response = $this->controller->destroy(1);

        $this->assertNull($response);
    }

}