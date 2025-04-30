<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UbersuggestController extends Controller
{
    private $apiEndpoint = 'https://api.dataforseo.com/v3/seo';
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return Inertia::render('Seo/Ubersuggest/Index');
    }

    public function suggestKeywords(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:255',
            'location' => 'required|string',
            'language' => 'required|string'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.dataforseo.api_key')
            ])->post($this->apiEndpoint . '/keyword_suggestions', [
                'keyword' => $request->keyword,
                'location' => $request->location,
                'language' => $request->language
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            throw new \Exception('Failed to get keyword suggestions');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch keyword suggestions',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}