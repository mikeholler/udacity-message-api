<?php

use \Mockery as m;

class InboxControllerTest extends TestCase {

    /**
     * @var m\Mock
     */
    protected $messageModel;

    /**
     * @var m\Mock
     */
    protected $userModel;

    public function setUp() {
        parent::setUp();

        $this->messageModel = $this->mock('MessageModel');
        $this->userModel = $this->mock('UserModel');
    }

    public function tearDown() {
        m::close();
        parent::tearDown();
    }

    public function testIndex() {
        $data = ['data'];
        $this->userModel->shouldReceive('getOne')->once()->with(1)->andReturn(1);
        $this->messageModel->shouldReceive('getInbox')->once()
            ->andReturn($data);

        $response = $this->call('GET', 'users/1/inbox');

        $this->assertResponseOk();
        $this->assertEquals(json_encode($data), $response->getContent());
    }

    public function testShow() {
        $data = ['key' => 'value'];
        $this->userModel->shouldReceive('getOne')->once()->with(1)->andReturn(1);
        $this->messageModel->shouldReceive('getMessage')->once()
            ->with(1, 2)->andReturn($data);

        $response = $this->call('GET', 'users/1/inbox/2');

        $this->assertResponseOk();
        $this->assertEquals(json_encode($data), $response->getContent());
    }

    public function testDelete() {
        $this->userModel->shouldReceive('getOne')->once()->with(1)->andReturn(1);
        $this->messageModel->shouldReceive('deleteMessage')->once()
            ->with(1, 2)->andReturn(null);

        $this->call('DELETE', 'users/1/inbox/2');

        $this->assertResponseOk();
    }
}