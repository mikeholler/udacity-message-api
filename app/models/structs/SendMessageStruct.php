<?php

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Defines what a sent message looks like.
 */
class SendMessageStruct extends BaseStruct {

    /**
     * Set to true to send the message to all users.
     *
     * @var bool
     */
    public $broadcast;

    /**
     * Users to send message to.
     *
     * @var array
     */
    public $toUsers;

    /**
     * Groups to send message to.
     *
     * @var array
     */
    public $toGroups;

    /**
     * Subject of message.
     *
     * @var string
     */
    public $subject;

    /**
     * Body of message;
     *
     * @var string
     */
    public $body;

    /**
     * @throws BadRequestHttpException
     */
    public function validate() {
        // No recipient means invalid request.
        if (!($this->broadcast or $this->toUsers or $this->toGroups))
            throw new BadRequestHttpException('Must provide at least one recipient');

        // All the fields should be the appropriate type (or null if optional).
        if (!self::isBoolOrNull($this->broadcast))
            throw new BadRequestHttpException('broadcast must be bool or null');

        if (!self::isArrayOrNull($this->toUsers))
            throw new BadRequestHttpException('toUsers must be array or null');

        if (!self::isArrayOrNull($this->toGroups))
            throw new BadRequestHttpException('toGroups must be array or null');

        if (!is_string($this->subject))
            throw new BadRequestHttpException('subject cannot be null');

        if (!is_string($this->body))
            throw new BadRequestHttpException('body cannot be null');
    }

}