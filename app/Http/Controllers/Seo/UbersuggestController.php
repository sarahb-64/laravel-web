<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UbersuggestController extends Controller
{
    public function index()
    {
        return view('seo.ubersuggest.index');
    }

    public function suggestKeywords(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:255',
        ]);

        try {
            // Here you would typically make an API call to Ubersuggest
            // For now, we'll return a mock response
            $suggestions = [
                $request->keyword . ' tips',
                'best ' . $request->keyword,
                'how to ' . $request->keyword,
                $request->keyword . ' guide',
                $request->keyword . ' tutorial',
            ];

            return response()->json([
                'success' => true,
                'data' => $suggestions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching keyword suggestions: ' . $e->getMessage()
            ], 500);
        }
    }
}