<?php

namespace App\Http\Controllers;

use App\Services\AnswerThePublicService;
use Illuminate\Http\Request;

class AnswerThePublicController extends Controller
{
    protected $service;

    public function __construct(AnswerThePublicService $service)
    {
        $this->middleware('auth');
        $this->service = $service;
    }

    public function index()
    {
        return view('seo.answer-the-public', [
            'header' => 'AnswerThePublic - Generador de Preguntas'
        ]);
    }

    public function getSuggestions(Request $request)
    {
        $keyword = $request->input('keyword');
        
        if (empty($keyword)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Keyword is required'
            ], 400);
        }

        try {
            $suggestions = $this->service->getAutocompleteSuggestions($keyword);
            
            return response()->json([
                'status' => 'success',
                'data' => $suggestions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch suggestions: ' . $e->getMessage()
            ], 500);
        }
    }
}