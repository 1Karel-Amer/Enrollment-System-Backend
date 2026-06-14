<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    public function getWeather(Request $request, $city)
    {
        // Cache the weather data for 15 minutes (900 seconds)
        return Cache::remember("weather_{$city}", 900, function () use ($city) {
            $apiKey = env('WEATHER_API_KEY');
            
            // Added ->withoutVerifying() to fix the cURL 77 SSL error
            $response = Http::withoutVerifying()->get("https://api.openweathermap.org/data/2.5/forecast", [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric'
            ]);

            return $response->json();
        });
    }
}