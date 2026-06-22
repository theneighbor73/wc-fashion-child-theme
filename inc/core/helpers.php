<?php

/**
 * Enqueue JS helper
 *
 * @param string $handle
 * @param string $relative_path
 * @param array  $dependencies
 * @param bool   $in_footer
 */

function cpsc_enqueue_script(
    $handle,
    $relative_path,
    $dependencies = [],
    $in_footer = true
) {

    $full_path = get_theme_file_path($relative_path);

    wp_enqueue_script(
        $handle,
        get_theme_file_uri($relative_path),
        $dependencies,
        file_exists($full_path)
            ? filemtime($full_path)
            : null,
        $in_footer
    );
}

/**
 * Enqueue CSS helper
 *
 * @param string $handle
 * @param string $relative_path
 * @param array  $dependencies
 */

function cpsc_enqueue_style(
    $handle,
    $relative_path,
    $dependencies = []
) {

    $full_path = get_theme_file_path($relative_path);

    wp_enqueue_style(
        $handle,
        get_theme_file_uri($relative_path),
        $dependencies,
        file_exists($full_path)
            ? filemtime($full_path)
            : null
    );
}
