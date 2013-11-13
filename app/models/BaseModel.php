<?php

class BaseModel {

    /**
     * Qualify an attribute or table name by another name.
     *
     * Example usage:
     *
     * qualify('table', 'attr') == 'table.attr'
     *
     * @param array $pieces
     *
     * @return string
     */
    protected static function qualify(array $pieces) {
        return implode('.', $pieces);
    }

}