<?php

use \Mockery as m;

class InboxControllerTest extends TestCase {

    /**
     * @var InboxController
     */
    protected $controller;

    /**
     * @var MessageModel
     */
    protected $messageModel;

    public function setUp() {
        parent::setUp();

        $this->messageModel = m::mock('MessageModel');
        $this->controller = new InboxController($this->messageModel);
    }

    public function tearDown() {
        m::close();
    }

    public function testIndex() {
        $this->messageModel->shouldReceive('getInbox')->once()
            ->andReturn(['notEmpty']);

        $response = $this->controller->index(1);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('["notEmpty"]', $response->getContent());
    }

    public function testShow() {
        $this->messageModel->shouldReceive('getMessage')->once()
            ->with(1, 2)->andReturn(['notEmpty']);

        $response = $this->controller->show(1, 2);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('["notEmpty"]', $response->getContent());
    }

    public function testDelete() {
        $this->messageModel->shouldReceive('deleteMessage')->once()
            ->with(1, 2)->andReturn(null);

        $response = $this->controller->destroy(1, 2);

        $this->assertNull($response);
    }
}