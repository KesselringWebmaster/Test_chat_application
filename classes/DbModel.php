<?php

/**
 * Class DbModel
 * Main parent of the models that initializes database $_db field.
 */
class DbModel
{

    protected $_db = null;

    public function __construct()
    {
        $this->_db = SafeMySQL::getInstance();
    }

}
