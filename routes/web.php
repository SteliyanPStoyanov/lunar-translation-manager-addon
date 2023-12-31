<?php

use Illuminate\Support\Facades\Route;
use Lunar\Hub\Http\Middleware\Authenticate;
use Lunar\TranslationManager\Http\Livewire\Pages\TranslationIndex;
use Illuminate\Support\Facades\App;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'prefix' => config('lunar-hub.system.path', 'hub'),
    'middleware' => config('lunar-hub.system.middleware', ['web']),
], function () {
    Route::group([
        'middleware' => [
            Authenticate::class,
        ],
    ], function () {
        Route::group([
            'prefix' => 'translation',
            'middleware' => 'can:manage-translation',
        ], function () {
            Route::get('/', TranslationIndex::class)->name('hub.translation.index');
        });



    });
});

