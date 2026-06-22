<?php

define("SITE_URL", "http://localhost:8081");
define("MAX_LOGIN_ATTEMPTS", 5);
if (!defined("CUSTOM_PREFIX")) {
    define("CUSTOM_PREFIX", "12asfQRF1");
}

// Load parent style handle
const PARENT_STYLE_HANDLE = "custom-print-shop-basic-style";
// Define child style handle
const CHILD_STYLE_HANDLE = "cpsc-basic-style";

// For logo resize customization feature
const CPSC_DEFAULT_LOGO_RESIZE = 0;

// For logo ratio setting customization feature
const CPSC_LOGO_RATIOS = [
    20 => [
        'label'  => '2:1',
        'width'  => 200,
        'height' => 100,
        'css'    => '2 : 1',
    ],
    30 => [
        'label'  => '3:1',
        'width'  => 300,
        'height' => 100,
        'css'    => '3 : 1',
    ],
    40 => [
        'label'  => '4:1',
        'width'  => 400,
        'height' => 100,
        'css'    => '4 : 1',
    ],
    53 => [
        'label'  => '5:3',
        'width'  => 250,
        'height' => 150,
        'css'    => '5 : 3',
    ],
];
