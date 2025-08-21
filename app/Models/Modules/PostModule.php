<?php

namespace App\Models\Modules;

use App\Models\Posts\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostModule extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_modules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module_content',
        'post_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'module_content' => 'array',
    ];

    /**
     * Get the post that owns the post module.
     *
     * @return BelongsTo<Post, PostModule>
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    /**
     * Calculate the estimated reading time of the module content.
     *
     * @return int Estimated reading time in minutes.
     */
    public function readingTime(): int
    {
        $content = $this->module_content ?? [];

        if (is_string($content)) {
            $content = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        }

        $extractText = function (array $items) use (&$extractText): string {
            $text = '';

            foreach ($items as $item) {
                if (is_array($item)) {
                    $text .= match (true) {
                        isset($item['value']) => ' ' . $item['value'],
                        isset($item['title']) => ' ' . $item['title'],
                        isset($item['heading']) => ' ' . $item['heading'],
                        isset($item['paragraph']) => ' ' . $item['paragraph'],
                        isset($item['quote']) => ' ' . $item['quote'],
                        default => ' ' . $extractText($item),
                    };
                }
            }

            return $text;
        };

        $allText = $extractText($content);
        $wordCount = str_word_count($allText);

        return (int) ceil($wordCount / 200);
    }
}
