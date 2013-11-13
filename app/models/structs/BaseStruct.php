<?php

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Defines structures to store user input.
 */
abstract class BaseStruct {
    /**
     * Validate the integrity of the struct.
     *
     * @throws BadRequestHttpException
     */
    abstract function validate();

    protected static function isBoolOrNull($item) {
        return is_bool($item) or $item === null;
    }

    protected static function isArrayOrNull($item) {
        return is_array($item) or $item === null;
    }
}