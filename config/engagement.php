<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Engagement Metrics Configuration
    |--------------------------------------------------------------------------
    |
    | Define all engagement metrics for your content along with default values
    | and slider configurations (min, max, step).
    |
    */

    'metrics' => [
        'views' => [
            'label' => 'Views Weight',
            'default' => 0.2,
            'min' => 0,
            'max' => 1,
            'step' => 0.1,
        ],
        'likes' => [
            'label' => 'Likes Weight',
            'default' => 0.2,
            'min' => 0,
            'max' => 1,
            'step' => 0.1,
        ],
        'comments' => [
            'label' => 'Comments Weight',
            'default' => 0.4,
            'min' => 0,
            'max' => 1,
            'step' => 0.1,
        ],
        'ratings' => [
            'label' => 'Ratings Weight',
            'default' => 0.1,
            'min' => 0,
            'max' => 1,
            'step' => 0.05,
        ],
        'shares' => [
            'label' => 'Shares Weight',
            'default' => 0.1,
            'min' => 0,
            'max' => 1,
            'step' => 0.05,
        ],
    ],

];
