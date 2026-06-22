<?php

/**
 * Write debug info into child theme debug.log
 *
 * @param mixed $message
 * @param string $group
 */

function cpsc_debug_logger(
    $message,
    $group = 'GENERAL'
) {
    if (! WP_DEBUG) {
        return;
    }

    $line =
        '['
        . current_time('mysql')
        . ']'
        . '['
        . $group
        . '] ';

    if (
        is_array($message)
        ||
        is_object($message)
    ) {
        $message =
            print_r(
                $message,
                true
            );
    }

    file_put_contents(

        get_stylesheet_directory()
            . '/debug.log',

        $line
            . $message
            . PHP_EOL,

        FILE_APPEND

    );
}
