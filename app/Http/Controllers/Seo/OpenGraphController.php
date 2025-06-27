<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class OpenGraphController extends Controller
{
    public function index()
    {
        return view('seo.open-graph', [
            'projects' => auth()->user()->projects
        ]);
    }

    public function analyze(Project $project)
    {
        $this->authorize('view', $project);
        
        try {
            $response = Http::get($project->url);
            $html = $response->body();
            
            // Extract OpenGraph tags
            $ogTags = $this->extractOpenGraphTags($html);
            
            return response()->json([
                'current' => $ogTags,
                'suggestions' => $this->getAiSuggestions($ogTags, $html)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to analyze URL: ' . $e->getMessage()
            ], 500);
        }
    }

    public function analyzeUrl(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $response = Http::get($request->url);
            $html = $response->body();
            
            // Extract OpenGraph tags
            $ogTags = $this->extractOpenGraphTags($html);
            
            return response()->json([
                'current' => $ogTags,
                'suggestions' => $request->has('use_ai') 
                    ? $this->getAiSuggestions($ogTags, $html) 
                    : []
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to analyze URL: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function extractOpenGraphTags($html)
    {
        $ogTags = [];
        preg_match_all('/<meta\s+property="og:([^"]+)"\s+content="([^"]*)"/i', $html, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $index => $property) {
                $ogTags[$property] = $matches[2][$index] ?? '';
            }
        }
        
        return $ogTags;
    }

    protected function getAiSuggestions($currentTags, $html)
    {
        // This would call the OpenAI API in production
        // For testing, it's mocked in the test class
        return \OpenAI::client(config('services.openai.secret'))
            ->chat()
            ->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an SEO expert. Analyze the following HTML and current OpenGraph tags, then suggest improved ones.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Current OpenGraph tags: " . json_encode($currentTags) . 
                                    "\n\nHTML content: " . substr(strip_tags($html), 0, 4000)
                    ]
                ]
            ]);
    }
}