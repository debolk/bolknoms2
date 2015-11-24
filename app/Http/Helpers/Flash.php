<?php

namespace App\Http\Helpers;

/**
 * Allows you to easily set and display flash messages
 */
class Flash
{
    /**
     * Available types
     */
    const WARNING = 'warning';
    const ERROR = 'error';
    const SUCCESS = 'success';

    /**
     * Location (index) to store the messages
     */
    private static $index = 'messages';

    /**
     * Sets a flash message
     * @param const $type either warning, error or success
     * @param string $message
     * @return void
     */
    public static function set($type, $message)
    {
        if (!in_array($type, array(Flash::ERROR, Flash::WARNING, Flash::SUCCESS))) {
            throw new InvalidArgumentException('Type of flash message should be a defined constant');
        }

        $messages = \Session::get(self::$index);
        if ($messages === null) {
            $messages = [];
        }
        $messages[] = ['type' => $type, 'message' => $message];
        \Session::flash(self::$index, $messages);
    }

    /**
     * Display all messages formatted
     * @return string
     */
    public static function display_messages()
    {
        $messages = \Session::get(self::$index);
        if (sizeof($messages) > 0) {
            return \View::make('layouts/_messages', ['messages' => $messages]);
        }
        else {
            return '';
        }
    }
}
