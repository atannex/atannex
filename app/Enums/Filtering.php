<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use App\Models\Posts\Post;
use Illuminate\Support\Collection;

final class Filtering extends Enum
{
    const FEATURED = 'featured';
    const TRENDING = 'trending';
    const LATEST = 'latest';
    const MOST_COMMENTED = 'most_commented';

    public static function labels(): array
    {
        return [
            self::FEATURED => 'Featured Posts',
            self::TRENDING => 'Trending Posts',
            self::LATEST => 'Latest Posts',
            self::MOST_COMMENTED => 'Most Commented',
        ];
    }

    public function posts(): Collection
    {
        switch ($this->value) {
            case self::FEATURED:
                return Post::where('is_published', true)->latest()->take(10)->get();

            case self::TRENDING:
                return Post::orderByDesc('views')->take(10)->get();

            case self::LATEST:
                return Post::latest()->take(10)->get();

            case self::MOST_COMMENTED:
                return Post::withCount('comments')
                    ->orderByDesc('comments_count')
                    ->take(10)
                    ->get();

            default:
                return collect();
        }
    }
}
