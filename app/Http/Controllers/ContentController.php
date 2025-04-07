<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
        ]);

        $prompt = $request->input('prompt');
        $generatedContent = $this->aiService->generateContent($prompt);

        if ($generatedContent) {
            return view('content.result', ['content' => $generatedContent]);
        }

        return redirect()->back()->with('error', 'Failed to generate content.');
    }
}