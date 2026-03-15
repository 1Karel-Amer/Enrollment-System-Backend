<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    public function getWeather(Request $request, $city)
    {
        
        return Cache::remember("weather_{$city}", 900, function () use ($city) {
            $apiKey = env('WEATHER_API_KEY');
            $response = Http::get("https://api.openweathermap.org/data/2.5/forecast", [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric'
            ]);

            return $response->json();
        });
    }
}
