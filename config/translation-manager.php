<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Available Application Locales
    |--------------------------------------------------------------------------
    |
    | The available application locales that can be used.
    | For flag codes, please refer to https://flagicons.lipis.dev/ (e.g. nl for Netherlands).
    |
    */

    'available_locales' => [
        ['code' => 'en', 'name' => 'English', 'flag' => 'gb'],
        ['code' => 'bg', 'name' => 'Bulgarian', 'flag' => 'bg'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Language Switcher
    |--------------------------------------------------------------------------
    |
    | Enable the language switcher feature in top bar.
    |
    */

    'language_switcher' => true,


    'show_flags' => true,
    /*
    |--------------------------------------------------------------------------
    | Translation methods
    |--------------------------------------------------------------------------
    |
    | Update this array to tell the package which methods it should look for
    | when finding missing translations.
    |
    */
    'translation_methods' => ['trans', '__'],

    /*
    |--------------------------------------------------------------------------
    | Scan paths
    |--------------------------------------------------------------------------
    |
    | Update this array to tell the package which directories to scan when
    | looking for missing translations.
    |
    */
    'scan_paths' => [
        app_path(),
        resource_path(),
        app()->basePath('vendor/lunarphp'),
        app()->basePath('vendor/lunar-banner'),
        app()->basePath('vendor/lunar-translation-manager'),
        app()->basePath('vendor/lunar-static-pages')
    ],
];
