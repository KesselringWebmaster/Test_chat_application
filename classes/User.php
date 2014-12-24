<?php

/**
 * Class User
 */
class User extends DbModel
{

    private $_id = null;
    private $_nickName = null;
    private $_tableName = 'user';

    public function __construct()
    {
        parent::__construct();
        $this->_registerCookies();
        $this->_insert();
    }

    /**
     * Registering user entering chat time in cookies
     */
    private function _registerCookies()
    {
        if (!isset($_COOKIE['user'])) {
            $this->_nickName = $this->_generateNickName();
        } else {
            $this->_nickName = $_COOKIE['user'];
        }
        setcookie('user', $this->_nickName, time() + Config::$settings['session_time_limit'], "/");
    }

    /**
     * Generates random string to be used as user nick
     * @return string
     */
    private function _generateNickName()
    {
        $validCharacters = '12345abcdefghijklmnopqrstuvwxyz678910';
        $validCharNumber = strlen($validCharacters);
        $result = "";
        $length = Config::$settings['nick_name_length'];
        for ($i = 0; $i < $length; $i++) {
            $result .= $validCharacters[mt_rand(0, ($validCharNumber - 1))];
        }
        return $result;
    }

    /**
     * Inserting user to a database table
     * Returns user id on success and FALSE on fail.
     * @return bool|int
     */
    private function _insert()
    {
        $id = $this->_db->getOne("SELECT id FROM ?n WHERE nick_name = ?s ", $this->_tableName, $this->_nickName);
        if (!empty($id)) {
            $this->_id = $id;
            return $id;
        }

        $result = $this->_db->query("INSERT INTO ?n SET ?u", $this->_tableName, array("nick_name" => $this->_nickName));
        if (!$result) {
            return FALSE;
        }
        $this->_id = $this->_db->insertId();
        return $this->_id;
    }

    /**
     * Getting user id
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Getting user nick name
     * @return string
     */
    public function getNickName()
    {
        return trim($this->_nickName);
    }

    /**
     * Inserting message to a separate user table
     * Returns new message id on success and FALSE on fail.
     * @param string $message
     * @return bool|int
     */
    public function insertToUserMessageStorage($message)
    {
        if (empty($this->_id))
            return FALSE;

        $tmpTableName = 'user_message_' . $this->_id;
        $this->_db->query("CREATE TABLE IF NOT EXISTS ?n (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `message` varchar(255) NOT NULL,
                   PRIMARY KEY (`id`),
                   KEY `time` (`time`)
                   ) ENGINE = MyISAM DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;", $tmpTableName);

        $result = $this->_db->query("INSERT INTO ?n SET ?u", $tmpTableName, array("message" => $message));
        if (!$result)
            return FALSE;
        return $this->_db->insertId();
    }

}
