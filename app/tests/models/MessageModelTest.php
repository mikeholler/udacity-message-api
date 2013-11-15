<?php

class MessageModelTest extends ModelTestCase {

    /**
     * @var MessageModel
     */
    protected $model;

    public function setUp()
    {
        parent::setUp();

        $this->model = new MessageModel;

        $this->seedDatabase(
            ['UserTableSeeder', 'GroupTableSeeder', 'MemberTableSeeder', 'MessageTablesSeeder']
        );
    }

    public function testGetInbox() {
        $inbox = $this->model->getInbox(2);

        $this->assertEquals(3, count($inbox));
        $this->assertEquals(1, $inbox[0]->messageId);
        $this->assertEquals(2, $inbox[1]->messageId);
        $this->assertEquals(3, $inbox[2]->messageId);
    }

    public function testGetInboxLimit() {
        $inbox = $this->model->getInbox(2, 1);

        $this->assertEquals(1, count($inbox));
        $this->assertEquals(1, $inbox[0]->messageId);
    }

    public function testGetInboxLimitOffset() {
        $inbox = $this->model->getInbox(2, 1, 1);

        $this->assertEquals(1, count($inbox));
        $this->assertEquals(2, $inbox[0]->messageId);
    }

    public function testGetMessageAndMarkRead() {
        $message = $this->model->getMessage(2, 1);
        $messageReceipt = DB::table('messageReceive')
            ->where('toUser', 2)
            ->where('messageId', 1)
            ->first();

        $this->assertEquals(1, $message->messageId);
        $this->assertEquals('First Message Subject', $message->subject);
        $this->assertEquals('First body.', $message->body);
        $this->assertEquals(1, $messageReceipt->read);
    }

    public function testDeleteMessage() {
        $this->model->deleteMessage(2, 1);

        $message = DB::table('messageReceive')
            ->where('toUser', 2)
            ->where('messageId', 1)
            ->first();

        $this->assertNull($message);
    }

    public function testSendBroadcastMessage() {
        $message = new SendMessageStruct;
        $message->broadcast = true;
        $message->subject = 'subject';
        $message->body = 'body';

        $messageId = $this->model->sendMessage(1, $message);

        // check that all users except the sender get the message
        $recipients = DB::table('messageReceive')
            ->where('messageId', $messageId)
            ->lists('toUser');

        $numUsers = DB::table('user')->count();
        $senderReceipt = DB::table('messageReceive')
            ->where('messageId', $messageId)
            ->where('toUser', 1)
            ->first();

        $this->assertEquals($numUsers - 1, count($recipients));
        $this->assertNull($senderReceipt);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testSendBroadcastMessageFail() {
        Artisan::call('migrate:refresh');
        DB::table('user')->insert(['userId' => 1, 'username' => 'sender']);

        $message = new SendMessageStruct;
        $message->toGroups = ['apple'];
        $message->subject = 'subject';
        $message->body = 'body';

        $this->model->sendMessage(1, $message);
    }

    public function testSendGroupMessage() {
        // By using group 3 (groupName = 'all'), which contains all
        // users including the sender, we reduce the problem
        // to that of testing a broadcast message.
        $message = new SendMessageStruct;
        $message->toGroups = ['all', 'apple'];
        $message->subject = 'subject';
        $message->body = 'body';

        $messageId = $this->model->sendMessage(1, $message);

        // check that all users except the sender get the message
        $recipients = DB::table('messageReceive')
            ->where('messageId', $messageId)
            ->lists('toUser');

        $numUsers = DB::table('user')->count();
        $senderReceipt = DB::table('messageReceive')
            ->where('messageId', $messageId)
            ->where('toUser', 1)
            ->first();

        $this->assertEquals($numUsers - 1, count($recipients));
        $this->assertNull($senderReceipt);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testSendGroupMessageFail() {
        $message = new SendMessageStruct;
        $message->toGroups = ['notARealGroup'];
        $message->subject = 'subject';
        $message->body = 'body';

        $this->model->sendMessage(1, $message);
    }

    public function testSendUserMessage() {
        // As with the test for group send message, by "sending"
        // the message to every single user, including the
        // sender, we make this as easy to test as sending a
        // broadcast message.
        $message = new SendMessageStruct;
        $message->toUsers = ['sender', 'recipient', 'other', 'doesNotExist'];
        $message->subject = 'subject';
        $message->body = 'body';

        $messageId = $this->model->sendMessage(1, $message);

        // check that all users except the sender get the message
        $recipients = DB::table('messageReceive')
            ->where('messageId', $messageId)
            ->lists('toUser');

        $numUsers = DB::table('user')->count();
        $senderReceipt = DB::table('messageReceive')
            ->where('messageId', $messageId)
            ->where('toUser', 1)
            ->first();

        $this->assertEquals($numUsers - 1, count($recipients));
        $this->assertNull($senderReceipt);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testSendUserMessageFail() {
        $message = new SendMessageStruct;
        $message->toUsers = ['notARealUser'];
        $message->subject = 'subject';
        $message->body = 'body';

        $this->model->sendMessage(1, $message);
    }

    public function testMarkRead() {
        $this->model->markRead(2, 1);

        $results = DB::table('messageReceive')
            ->where('messageId', 1)
            ->where('toUser', 2)
            ->first();

        $this->assertTrue((boolean) $results->read);
    }

}