<?php

namespace App\Http\Traits;

use App\Enums\Flag;
use App\Enums\Subject;
use App\Models\Others\About;

trait HasContact
{
    public function contact()
    {
        $contact = About::query()
            ->where('flag', Flag::PUBLISHED)
            ->firstOrFail();

        $map = $contact->map;

        $infos = collect($contact->info)->map(function ($info) {
            return [
                'icon' => $info['icon'],
                'title' => $info['title'],
                'items' => collect($info['details'])->map(function ($detail) {
                    return [
                        'type' => strtolower($detail['type']),
                        'value' => $detail['value'],
                        'label' => $detail['value'],
                    ];
                })->toArray(),
            ];
        });

        $subjects = Subject::asSelectArray();

        return view('contact', ['infos' => $infos, 'map' => $map, 'subjects' => $subjects]);
    }
}
