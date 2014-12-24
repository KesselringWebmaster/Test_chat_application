<?php

/**
 * Class Message
 */
class Message extends DbModel
{

    private $_tableName = 'message';
    private $_id = null;
    private $_userId = null;
    private $_message = null;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Sets massage author id
     * @param $userId
     */
    public function setUserId($userId)
    {
        $this->_userId = $userId;
    }

    /**
     * Getting message value
     * @return string message
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * Setting message text
     * @param $message
     */
    public function setMessage($message)
    {
        $this->_message = $message;
    }

    /**
     * Getting chat messages from the database.
     * @return array|bool
     */
    public function getChatMessages()
    {
        $max = $this->_db->getOne("SELECT max(id) FROM ?n", $this->_tableName);
        $data = $this->_db->getAll("SELECT u.nick_name, m.time, m.message FROM ?n as m "
            . "LEFT JOIN ?n AS u ON m.user_id = u.id "
            . "WHERE m.id BETWEEN  ?i AND  ?i ORDER BY m.id", $this->_tableName, 'user', ($max - Config::$settings['last_message_count'] + 1), $max);
        if (empty($data))
            return FALSE;
        return $data;
    }

    /**
     * Inserting message to a database table
     * Return inserted message id on success, FALSE on fail.
     * @return bool|int
     */
    public function insert()
    {
        $result = $this->_db->query("INSERT INTO ?n SET ?u", $this->_tableName, array(
            "user_id" => $this->_userId,
            "message" => $this->_message,
        ));
        if (!$result)
            return FALSE;

        $this->_id = $this->_db->insertId();
        return $this->_id;
    }

}
