<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/platforms')->name('platforms.')->group(function () {
    Route::get('/', \App\Http\Livewire\Platform\PlatformIndex::class)->name('index');
    Route::get('/create', \App\Http\Livewire\Platform\CreateAndEdit::class)->name('create');
});
