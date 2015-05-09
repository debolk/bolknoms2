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
            return \View::make('flash/messages', ['messages' => $messages]);
        }
        else {
            return '';
        }
    }

    /**
     * Formats errors in a readable way using HTML
     * @param array $validation_errors the array with Laravel validation errors
     * @return string HTML-code for friendly display of errors
     */
    public static function error_messages_for($validation_errors)
    {
        // Don't output anything when the errors-array is empty
        if ($validation_errors === null) {
            return '';
        }

        echo '<div class="notification error">';
        echo '<p><strong>De wijzigingen konden niet worden opgeslagen:</strong></p>';

        echo '<ul>';

        foreach ($validation_errors->all() as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul></div>';
    }
}
