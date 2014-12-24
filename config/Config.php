<?php
date_default_timezone_set('UTC');

/**
 * Class Config
 * Static class to store site settings and database connection options
 */
class Config
{

    /*General array of the site settings*/
    public static $settings = array(
        'last_message_count' => 10,
        'oldest_messages_number' => 50,
        'last_symbols_number' => 5,
        'session_time_limit' => 28800, /* 8 hours */
        'nick_name_length' => 10,
        'create_user_message_table' => TRUE,
    );

    /*Database connection options*/
    public static $db = array(
        'user' => 'me',
        'pass' => 'jcBcNzvTYXqGHb2q',
        'db' => 'chat_db',
        'charset' => 'utf8',
    );

}
