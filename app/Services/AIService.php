<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY'); // Get the API key from the .env file
    }

    public function generateContent($prompt)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post('https://api.openai.com/v1/completions', [
            'model' => 'text-davinci-003', // or the model you are using
            'prompt' => $prompt,
            'max_tokens' => 500,
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['text'] ?? null;
        }

        return null; // If the request fails, return null
    }
}