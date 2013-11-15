<?php

/**
 * Base class all database models should inherit from.
 *
 * Laravel provides ORM support with Eloquent, but using classic models
 * allow for finer tooling of database queries and is also more easily
 * integration-tested.
 */
class BaseModel {

    /**
     * Qualify an attribute or table name by another name.
     *
     * Example usage:
     *
     * qualify('table', 'attr') == 'table.attr'
     *
     * @param array $pieces String array of identifiers, most general on the left.
     *
     * @return string
     */
    protected static function qualify(array $pieces)
    {
        return implode('.', $pieces);
    }

}