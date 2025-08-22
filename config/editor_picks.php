<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Matrix
    |--------------------------------------------------------------------------
    |
    | Default settings for retrieving editor weekly picks.
    | Start and end dates are handled dynamically in code.
    |
    */

    'limit' => 10,

    'weights' => [
        'views'    => 0.2,
        'likes'    => 0.2,
        'comments' => 0.4,
        'ratings'  => 0.1,
        'shares'   => 0.1,
    ],

    'min_score' => 0,
];
