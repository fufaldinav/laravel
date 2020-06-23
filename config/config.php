<?php
/**
 * @package php-tmdb\laravel
 * @author Mark Redeman <markredeman@gmail.com>
 * @copyright (c) 2014, Mark Redeman
 */

return [

    /*
    |--------------------------------------------------------------------------
    | TMDB API key
    |--------------------------------------------------------------------------
    */

    'api_key' => env('TMDB_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | TMDB Client options
    |--------------------------------------------------------------------------
    */

    'options' => [
        'secure' => true,
        'cache' => [
            'enabled' => true,
            'path' => storage_path('tmdb'),
        ],
        'log' => [
            'enabled' => true,
            'path' => storage_path('logs/tmdb.log'),
        ],

    ],
];
