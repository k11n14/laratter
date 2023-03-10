<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TweetController;

// π½ θΏ½ε 
use App\Http\Controllers\FavoriteController;

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

// γ³γ‘γ³γγ―ηη₯

// π½ γγγη·¨ι
Route::middleware('auth')->group(function () {
    // π½ θΏ½ε 
    Route::post('tweet/{tweet}/favorites', [FavoriteController::class, 'store'])->name('favorites');

    // π½ θΏ½ε 
    Route::post('tweet/{tweet}/unfavorites', [FavoriteController::class, 'destroy'])->name('unfavorites');



    // π½ θΏ½ε 
    Route::get('/tweet/mypage', [TweetController::class, 'mydata'])->name('tweet.mypage');

    Route::resource('tweet', TweetController::class);
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
