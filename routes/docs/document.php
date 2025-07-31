<?php

use Illuminate\Support\Facades\Route;

$typePattern = implode('|', config('reserved.types'));

Route::get('{type}', function ($type) {
    return view('documents.index', ['type' => $type]);
})->where('type', "^($typePattern)$")->name('document.index');

Route::get('{type}/{slug}', function ($type, $slug) {
    return view('documents.show', ['type' => $type, 'slug' => $slug]);
})->where('type', "^($typePattern)$")->name('document.show');
