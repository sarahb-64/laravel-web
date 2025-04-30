<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class AnswerThePublicService
{
    private $client;
    private $cacheDuration = 3600; // 1 hour

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getAutocompleteSuggestions($keyword)
    {
        // Cache key based on keyword
        $cacheKey = 'atp_suggestions_' . md5($keyword);
        
        // Try to get from cache first
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        try {
            // Google's autocomplete API endpoint
            $baseUrl = 'https://suggestqueries.google.com/complete/search';
            $params = [
                'client' => 'firefox',
                'q' => $keyword,
                'hl' => 'es', // Spanish language
                'ds' => 'yt',
            ];

            $response = $this->client->get($baseUrl, [
                'query' => $params,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Accept' => '*/*',
                    'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            // Cache the results
            Cache::put($cacheKey, $data[1], $this->cacheDuration);
            
            return $data[1];
        } catch (\Exception $e) {
            // Log the error
            \Log::error('AnswerThePublicService error: ' . $e->getMessage());
            return [];
        }
    }
}