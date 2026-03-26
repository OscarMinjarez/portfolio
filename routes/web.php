<?php

use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'index'])->name('home');
Route::post('/chat', [ChatController::class, 'process'])->name('chat.process');
Route::get('/chat/prompts', [ChatController::class, 'prompts'])->name('chat.prompts');
Route::get('/chat/greet', [ChatController::class, 'greet'])->name('chat.greet');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
