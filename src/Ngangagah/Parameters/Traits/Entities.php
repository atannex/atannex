<?php

declare(strict_types=1);

namespace Ngangagah\Parameters\Traits;

use App\Enums\PostType;


trait Entities
{
    private const ENTITY_MAPPING = [

        PostType::POST_BY_FONDOM => [
            'entity' => 'region',
            'idKey' => 'fondom_region_id'
        ],

        PostType::POST_BY_SUBDIVISION => [
            'entity' => 'region',
            'idKey' => 'subdivision_region_id'
        ],

        PostType::POST_BY_CATEGORY => [
            'entity' => 'category',
            'idKey' => 'category_id'
        ],
    ];
}
