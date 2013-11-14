<?php

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Defines structures to store user input.
 */
abstract class BaseStruct {
    /**
     * Populate object variables with items from a dictionary.
     *
     * @param array $data Associative
     */
    public function hydrate(array $data) {
        foreach ($data as $k => $v) {
            $this->{$k} = $v;
        }
    }

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