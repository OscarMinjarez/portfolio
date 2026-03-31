<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'index'])->name('home');

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

Route::post('/api/agent/insight', [AgentController::class, 'generateInsight'])->name('agent.generateInsight');
Route::post('/api/agent/details', [AgentController::class, 'generateDetails'])->name('agent.generateDetails');
Route::post('/api/agent/welcome', [AgentController::class, 'generateWelcome'])->name('agent.generateWelcome');

require __DIR__.'/settings.php';
