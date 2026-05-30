<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AyatController;

use App\Http\Controllers\SubtitleController;

Route::get(
    '/subtitle',
    [SubtitleController::class, 'index']
);

Route::get(
    '/subtitle/group/{id}',
    [SubtitleController::class, 'group']
);

Route::post(
    '/subtitle/group',
    [SubtitleController::class, 'storeGroup']
);

Route::post(
    '/subtitle/item',
    [SubtitleController::class, 'storeItem']
);

Route::post(
    '/subtitle/activate/{id}',
    [SubtitleController::class, 'activateItem']
);

Route::post(
    '/subtitle/next-subtitle',
    [SubtitleController::class, 'nextSubtitle']
);

Route::post(
    '/subtitle/prev-subtitle',
    [SubtitleController::class, 'prevSubtitle']
);

Route::post(
    '/subtitle/next-word',
    [SubtitleController::class, 'nextWord']
);

Route::post(
    '/subtitle/prev-word',
    [SubtitleController::class, 'prevWord']
);

Route::get(
    '/live-output',
    [SubtitleController::class, 'output']
);



Route::get('/', function () {
    return view('welcome');
});
