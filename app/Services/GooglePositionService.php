<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GooglePositionService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('SERPAPI_KEY'); // Leer la clave de SerpAPI desde el .env
    }

    public function getPosition($keyword)
    {
        if (!$this->apiKey) {
            return null;
        }

        // Fetch the current position from SerpAPI
        $response = Http::get('https://serpapi.com/search', [
            'q' => $keyword,
            'location' => 'Madrid, Spain',
            'api_key' => $this->apiKey
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $currentPosition = $data['organic_results'][0]['position'] ?? null;

            // If no position is found, return null
            if (!$currentPosition) {
                return null;
            }

            // Get the last recorded position for the given keyword from the database
            $lastPosition = KeywordPosition::where('keyword', $keyword)->latest()->first();

            // Save the current position in the database
            KeywordPosition::create([
                'keyword' => $keyword,
                'position' => $currentPosition,
                'url' => 'https://www.google.com/search?q=' . urlencode($keyword),
            ]);

            // Notify if the position change is significant (e.g., more than 5 positions)
            if ($lastPosition && abs($lastPosition->position - $currentPosition) > 5) {
                $users = User::all(); // You can customize this to notify specific users
                Notification::send($users, new PositionAlert($keyword, $currentPosition));
            }

            return $currentPosition;
        }

        return null;
    }
}