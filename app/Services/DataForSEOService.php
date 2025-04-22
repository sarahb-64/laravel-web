<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DataForSEOService
{
    public function fetchKeywordData(array $keywords, $language = 'es', $location = 2840)
    {
        $response = Http::withBasicAuth(
            env('DATAFORSEO_LOGIN'),
            env('DATAFORSEO_PASSWORD')
        )->post('https://api.dataforseo.com/v3/keywords_data/google_ads/search_volume/task_post',
        [
            [
                "keywords" => $keywords,
                "language_code" => $language,
                "location_code" => $location
            ]
        ]);

        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('DataForSEO API error: ' . $response->body());
    }

    public function fetchRankings(array $keywords, $domain, $language = 'es', $location = 2840)
    {
        $response = Http::withBasicAuth(
            env('DATAFORSEO_LOGIN'),
            env('DATAFORSEO_PASSWORD')
            )->post('https://api.dataforseo.com/v3/serp/google/organic/task_post',
        [
            [
                "keywords" => $keywords,
                "language_code" => $language,
                "location_code" => $location
            ]
        ]);

        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('DataForSEO API error: ' . $response->body());
    }
}