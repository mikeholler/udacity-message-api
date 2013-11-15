<?php

use \Mockery as m;

class InboxControllerTest extends TestCase {

    /**
     * @var m\Mock
     */
    protected $messageModel;

    public function setUp() {
        parent::setUp();

        $this->messageModel = $this->mock('MessageModel');
    }

    public function tearDown() {
        m::close();
        parent::tearDown();
    }

    public function testIndex() {
        $data = ['data'];
        $this->messageModel->shouldReceive('getInbox')->once()
            ->andReturn($data);

        $response = $this->call('GET', 'users/1/inbox');

        $this->assertResponseOk();
        $this->assertEquals(json_encode($data), $response->getContent());
    }

    public function testShow() {
        $data = ['key' => 'value'];
        $this->messageModel->shouldReceive('getMessage')->once()
            ->with(1, 2)->andReturn($data);

        $response = $this->call('GET', 'users/1/inbox/2');

        $this->assertResponseOk();
        $this->assertEquals(json_encode($data), $response->getContent());
    }

    public function testDelete() {
        $this->messageModel->shouldReceive('deleteMessage')->once()
            ->with(1, 2)->andReturn(null);

        $this->call('DELETE', 'users/1/inbox/2');

        $this->assertResponseOk();
    }
}