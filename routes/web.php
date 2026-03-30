<?php

use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'index'])->name('home');
Route::post('/chat', [ChatController::class, 'process'])->name('chat.process');
Route::get('/chat/prompts', [ChatController::class, 'prompts'])->name('chat.prompts');
Route::post('/chat/greet', [ChatController::class, 'greet'])->name('chat.greet');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::get('/api/weather', function () {
    return Cache::remember('weather_obregon', 1800, function () {
        $apiKey = env('OPENWEATHER_API_KEY');
        $url = "https://api.openweathermap.org/data/2.5/weather?q=Ciudad%20Obregon,MX&units=metric&appid={$apiKey}&lang=es";
        $response = Http::get($url);
        
        if ($response->successful()) {
            return $response->json();
        }
        return [
            'main' => ['temp' => 38], 
            'weather' => [['description' => 'cielo claro']]
        ];
    });
});

require __DIR__.'/settings.php';
