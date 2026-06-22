<?php

/**
 * Replace parent logo customization with child implementation.
 */

function cpsc_customizer_setup_overrides()
{
    /*
	|--------------------------------------------------------------------------
	| Disable parent logo system
	|--------------------------------------------------------------------------
	*/

    remove_action(
        'customize_register',
        'custom_print_shop_logo_customize_register'
    );

    remove_action(
        'customize_controls_enqueue_scripts',
        'custom_print_shop_customize_controls_js'
    );

    remove_filter(
        'get_custom_logo',
        'custom_print_shop_customize_logo_resize'
    );


    /*
	|--------------------------------------------------------------------------
	| Register child logo system instead
	|--------------------------------------------------------------------------
	*/

    if (
        function_exists(
            'cpsc_logo_customize_register'
        )
    ) {
        add_action(
            'customize_register',
            'cpsc_logo_customize_register'
        );
    }
}

add_action(
    'after_setup_theme',
    'cpsc_customizer_setup_overrides',
    20
);
