<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TweetController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/image', [ProfileController::class, 'updateImage'])->name('profile.updateImage');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tweet routes
    Route::get('/tweets', [TweetController::class, 'index'])->name('tweets.index');
    Route::post('/tweets', [TweetController::class, 'store'])->name('tweets.store');
    Route::post('/likes/{tweet}', [LikeController::class, 'like'])->name('likes.like');
    Route::delete('/tweets/{tweet}', [TweetController::class, 'destroy'])->name('tweets.destroy');
});

Route::middleware('auth')->prefix('api/tabs')->group(function () {
    Route::get('tweets', [TweetController::class, 'userTweets'])->name('tabs.tweets');
    Route::get('replies', [TweetController::class, 'userReplies'])->name('tabs.replies');
    Route::get('likes', [TweetController::class, 'userLikes'])->name('tabs.likes');
});

require __DIR__ . '/auth.php';
