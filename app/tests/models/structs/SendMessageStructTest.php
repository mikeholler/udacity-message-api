<?php

class SendMessageStructTest extends TestCase {

    /**
     * @var SendMessageStruct
     */
    protected $struct;

    public function setUp() {
        $this->struct = new SendMessageStruct;

    }

    public function testHydrate() {
        $data = [
            'broadcast' => true,
            'toUsers' => ["users"],
            'toGroups' => ["group1", "group2"],
            'subject' => 'subject',
            'body' => 'body'
        ];

        $this->struct->hydrate($data);

        $this->assertEquals($data['broadcast'], $this->struct->broadcast);
        $this->assertEquals($data['toUsers'], $this->struct->toUsers);
        $this->assertEquals($data['toGroups'], $this->struct->toGroups);
        $this->assertEquals($data['body'], $this->struct->body);
        $this->assertEquals($data['subject'], $this->struct->subject);
    }

    public function testValidateSuccessful() {
        $this->struct->broadcast = true;
        $this->struct->subject = 'subject';
        $this->struct->body = 'body';

        $this->struct->validate();
    }


    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testValidateFailureMultipleRecipientSpecifiers() {
        $this->struct->broadcast = true;
        $this->struct->toUsers = ["foo"];
        $this->struct->subject = 'subject';
        $this->struct->body = 'body';

        $this->struct->validate();
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testValidateFailureEmptyStruct() {
        $this->struct->validate();
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testValidateSubjectRequiredFailure() {
        $this->struct->broadcast = true;
        $this->struct->body = 'body';

        $this->struct->validate();
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testValidateBodyRequiredFailure() {
        $this->struct->broadcast = true;
        $this->struct->subject = 'subject';

        $this->struct->validate();
    }
}