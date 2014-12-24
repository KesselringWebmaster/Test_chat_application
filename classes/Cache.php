<?php

/**
 * Class Cache
 * Responsible for cache file generation
 */
class Cache extends DbModel
{

    private $_messageObj = null;
    private $_userObj = null;
    private $_cacheFilePath = 'cache/cache.json';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Setting a Message object to a class
     * @param Message $messageObj
     */
    public function setMessageObj(Message $messageObj)
    {
        $this->_messageObj = $messageObj;
    }

    /**
     * Setting a User object to a class
     * @param User $userObj
     */
    public function setUserObj(User $userObj)
    {
        $this->_userObj = $userObj;
    }

    /**
     * Getting an array of messages from the cache file
     * @return mixed. Array if not empty and FALSE if empty
     *
     */
    public function getMessagesFromCache()
    {
        if (file_exists($this->_cacheFilePath)) {
            $content = file_get_contents($this->_cacheFilePath);
            if (!empty($content)) {
                return json_decode($content, true);
            }
        }
        return FALSE;
    }

    /**
     * Rebuilding the cache. Assumed to save new message to log.
     * @return bool
     */
    public function rebuildCache()
    {
        if (file_exists($this->_cacheFilePath)) {
            $content = file_get_contents($this->_cacheFilePath);
            if (!empty($content)) {
                $content = json_decode($content, true);
                if (count($content) >= Config::$settings['last_message_count']) {
                    array_shift($content); /* removing the oldest one */
                }

                array_push($content, array('nick_name' => $this->_userObj->getNickName(),
                    'time' => gmdate('Y-m-d H:i:s'),
                    'message' => $this->_messageObj->getMessage(),
                )); /* adding a new one */
                $this->_substrMessageText($content);
                file_put_contents($this->_cacheFilePath, json_encode($content));
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * Substrigns message text to the needed length.
     * @param $array
     */
    private function _substrMessageText(&$array)
    {
        $count = count($array);
        if ($count < Config::$settings['last_message_count'])
            return;

        for ($i = 0; $i < Config::$settings['oldest_messages_number']; $i++) {
            $array[$i]['message'] = substr(strip_tags($array[$i]['message']), 0, Config::$settings['last_symbols_number']) . "...";
        }
    }

    /**
     * Builds the cache if not exists. Created if cache does not exists
     * @param Array $content
     * @return bool
     */
    public function buildCache($content)
    {
        if (!file_exists($this->_cacheFilePath)) {
            $this->_substrMessageText($content);
            file_put_contents($this->_cacheFilePath, json_encode($content));
            return TRUE;
        }
        return FALSE;
    }

}
