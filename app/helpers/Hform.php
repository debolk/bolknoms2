<?php

class Hform
{
    /**
     * Formats errors in a readable way using HTML
     * @param array
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
