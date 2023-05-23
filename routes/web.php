<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\HomeController as AdminController;
use App\Http\Controllers\Admin\GenreGameController;
use App\Http\Controllers\Admin\AdminGameController;
use App\Http\Controllers\User\ShopCartController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/', function () {
        return view('home.home');
    })->name('home');
});


// ************ Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::resource('genre-game', GenreGameController::class);
    Route::resource('game', AdminGameController::class);
});

Route::resource('user/shopcarts', ShopcartController::class);
