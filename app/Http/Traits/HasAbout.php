<?php

namespace App\Http\Traits;

use App\Enums\Flag;
use App\Models\Comments\Comment;
use App\Models\Others\About;
use App\Models\Posts\Post;
use App\Models\Regions\Employee;
use App\Models\User;

trait HasAbout
{
    public function about()
    {
        $about = About::query()
            ->where('flag', Flag::PUBLISHED)
            ->firstOrFail();

        $counters = collect($about->counters)->map(function ($counter) {
            $type = $counter['type'] ?? 'manual';

            switch ($type) {
                case 'years_experience':
                    $counter['number'] = now()->year - ($counter['base_year'] ?? now()->year);
                    break;

                case 'writers':
                    $counter['number'] = User::where('role', 'writer')->count();
                    break;

                case 'employees':
                    $counter['number'] = Employee::count();
                    break;

                case 'posts':
                    $counter['number'] = Post::count();
                    break;

                case 'users':
                    $counter['number'] = User::whereDoesntHave('employee')->count();
                    break;

                case 'comments':
                    $counter['number'] = Comment::count();
                    break;

                case 'translators':
                    $counter['number'] = User::where('role', 'translator')->count();
                    break;

                case 'manual':
                default:
                    $counter['number'] = $counter['number'] ?? 0;
                    break;
            }

            return $counter;
        });

        return view('about', [
            'about' => $about,
            'counters' => $counters,
        ]);
    }
}
