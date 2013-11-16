<?php

use \Mockery as m;

class UserControllerTest extends TestCase {

    /**
     * @var m\Mock
     */
    protected $userModel;

    /**
     * @var m\Mock
     */
    protected $messageModel;

    public function setUp() {
        parent::setUp();

        $this->userModel = $this->mock('UserModel');
        $this->messageModel = $this->mock('MessageModel');
    }

    public function tearDown() {
        m::close();
        parent::tearDown();
    }

    public function testIndex() {
        $data = ['data'];
        $this->userModel->shouldReceive('getAll')->once()
            ->with(1, 2)->andReturn($data);

        $response = $this->call('GET', 'users', ['limit' => '1', 'offset' => '2']);

        $this->assertResponseOk();
        $this->assertEquals(json_encode($data), $response->getContent());
    }

    public function testStore() {
        $this->userModel->shouldReceive('create')->once()
            ->with('foo');

        $response = $this->call(
            'POST',
            'users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"username":"foo"}'
        );

        $this->assertResponseOk();
        $this->assertEmpty($response->getContent());
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testStoreBadRequest() {
        $this->call('POST', 'users');
    }


    public function testShow() {
        $data = ['key' => 'value'];
        $this->userModel->shouldReceive('getOne')->with(1)
            ->andReturn($data);

        $response = $this->call(
            'GET',
            'users/1'
        );

        $this->assertResponseOk();
        $this->assertEquals(json_encode($data), $response->getContent());
    }

    public function testShowGroups() {
        $data = ['data'];
        $this->userModel->shouldReceive('getOne')->once()->with(1)->andReturn(1);
        $this->userModel->shouldReceive('getGroups')->once()
            ->with(1)->andReturn($data);

        $response = $this->call('GET', 'users/1/groups');

        $this->assertResponseOk();
        $this->assertEquals(json_encode($data), $response->getContent());
    }

    public function testDestroy() {
        $this->userModel->shouldReceive('getOne')->once()->with(1)->andReturn(1);
        $this->userModel->shouldReceive('delete')->once()->with(1);

        $this->call('DELETE', 'users/1');

        $this->assertResponseOk();
    }

    public function testSendMessage() {
        $data = [
            'broadcast' => true,
            'subject' => 'subject',
            'body' => 'body'
        ];

        $this->userModel->shouldReceive('getOne')->once()->with(1)->andReturn(1);
        $this->messageModel->shouldReceive('sendMessage')->once()
            ->with(1, m::type('SendMessageStruct'));

        $this->call(
            'POST',
            'users/1/send',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertResponseOk();
    }

}