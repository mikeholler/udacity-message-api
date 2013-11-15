<?php

use \Mockery as m;

class UserControllerTest extends TestCase {

    /**
     * @var UserController
     */
    protected $controller;

    /**
     * @var UserModel
     */
    protected $userModel;

    /**
     * @var MessageModel
     */
    protected $messageModel;

    public function setUp() {
        parent::setUp();

        $this->userModel = m::mock('UserModel');
        $this->messageModel = m::mock('MessageModel');

        $this->controller = new UserController($this->userModel, $this->messageModel);
    }

    public function tearDown() {
        m::close();
        parent::tearDown();
    }

    public function testIndex() {

        $this->userModel->shouldReceive('getAll')->once()
            ->andReturn(['notEmpty']);

        $response = $this->controller->index();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('["notEmpty"]', $response->getContent());
    }

    public function testShow() {
        $this->userModel->shouldReceive('getOne')->once()->with(1)
            ->andReturn(['notEmpty']);

        $response = $this->controller->show(1);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('["notEmpty"]', $response->getContent());
    }

    public function testShowGroups() {
        $this->userModel->shouldReceive('getGroups')->once()
            ->with(1)->andReturn(['notEmpty']);

        $response = $this->controller->showGroups(1);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('["notEmpty"]', $response->getContent());
    }

    public function testDestroy() {
        $this->userModel->shouldReceive('delete')->once()
            ->with(1);

        $response = $this->controller->destroy(1);

        $this->assertNull($response);
    }

}