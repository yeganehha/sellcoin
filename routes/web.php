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

Route::redirect('/' , '/orders');

Route::prefix('/platforms')->name('platforms.')->group(function () {
    Route::get('/', \App\Http\Livewire\Platform\PlatformIndex::class)->name('index');
    Route::get('/{platform}/edit', \App\Http\Livewire\Platform\CreateAndEdit::class)->name('edit');
    Route::get('/create', \App\Http\Livewire\Platform\CreateAndEdit::class)->name('create');
    Route::get('/{platform}/wallet/edit', \App\Http\Livewire\Platform\Wallet::class)->name('edit.wallet');

    Route::get('/{platform}/orders', \App\Http\Livewire\Order\OrderIndex::class)->name('orders');
});

Route::prefix('/orders')->name('orders.')->group(function () {
    Route::get('/', \App\Http\Livewire\Order\OrderIndex::class)->name('index');
    Route::get('/create' ,\App\Http\Livewire\Order\CreateOrder::class)->name('create');
    Route::get('/{order}/track' ,\App\Http\Livewire\Order\TrackOrder::class)->name('track');
});
