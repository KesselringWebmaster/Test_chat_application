<?php

/**
 * Class ApplicationController
 * Main handler of the application
 */
class ApplicationController
{

    private static $_instance = null;
    private $_userObj = null;
    private $_messageObj = null;
    private $_cacheObj = null;
    private $_isAjax = FALSE;

    private function __construct()
    {
        $this->_userObj = new User;
        $this->_messageObj = new Message;
        $this->_cacheObj = new Cache;
    }

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Getting messages to display.
     * If no messages in cache file it will build new cache file
     * Returns messages array or FALSE if there is not messages in the database
     * @return array|bool
     */
    public function getMessagesContent()
    {
        $content = $this->_cacheObj->getMessagesFromCache();
        if (empty($content)) {/* if cache empty */
            $content = $this->_messageObj->getChatMessages();
            if (!$content)
                return FALSE;

            $this->_cacheObj->buildCache($content); /* building cache */
        }
        return $content;
    }

    /**
     * Processing incoming post request and saves the message if present
     * If it is an ajax call, then outputs "Ok" in response and dies.
     */
    public function run()
    {
        $post = $this->_getPost();
        if (empty($post['message']))
            return FALSE;
        $this->_setIsAjax($post);
        $post['message'] = $this->_paint($post['message']);/*Paint message text when and where needed*/

        /* Insert in message table */
        $this->_messageObj->setUserId($this->_userObj->getId());
        $this->_messageObj->setMessage($post['message']);
        $this->_messageObj->insert();

        /* Insert in user message table if needed */
        if (Config::$settings['create_user_message_table'])
            $this->_userObj->insertToUserMessageStorage($post['message']);

        /* Rebuild cache file with a new message*/
        $this->_cacheObj->setMessageObj($this->_messageObj);
        $this->_cacheObj->setUserObj($this->_userObj);
        $result = $this->_cacheObj->rebuildCache();

        if ($result && $this->_isAjax) {
            echo 'Ok';
            exit;
        }
    }

    /**
     * Protects post request removing empty values and applying htmlentities to every not empty value
     * Returns text on success and FALSE if empty array passed.
     * @return array|bool
     */
    private function _getPost()
    {
        return $_POST;
        $post = array_filter($_POST); /* remove empty values */
        if (empty($post))
            return FALSE;
        array_walk($post, function (&$item) {
            $item = htmlentities($item,ENT_QUOTES, 'UTF-8');
        });
        return $post;
    }

    /**
     * Setting isAjax field based on post parameter
     * @param array $post
     */
    private function _setIsAjax($post)
    {
        if ($post['ajax'])
            $this->_isAjax = TRUE;

    }

    /**
     * Paints the message text when needed
     * @param string $text
     * @return bool|string
     */
    private function _paint($text)
    {
        if (empty($text))
            return FALSE;
        preg_match_all('/(?<!\w)@\w+/', $text, $matches);

        if (!empty($matches[0])) {
            $resulted = array();
            foreach ($matches[0] as $value) {
                $resulted[] = "<span class='nick-name-colored-text'>" . $value . "</span>";
            }
            $text = str_replace($matches[0], $resulted, $text);
        }

        if ($this->_isAjax)
            return "<span class='ajax-colored-text'>" . $text . "</span>";

        return $text;
    }

}
