<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Symfony\Component\DomCrawler\Crawler;
use OpenAI;

class MetaTagsController extends Controller
{
    /**
     * Display a list of projects for the user to choose from.
     */
    public function index()
    {
        $projects = Auth::user()->projects()->whereNotNull('url')->get();
        
        return Inertia::render('Seo/MetaTags/Index', [
            'projects' => $projects
        ]);
    }

    /**
     * Show the meta tag analysis tool for a specific project.
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);
        
        return Inertia::render('Seo/MetaTags/Show', [
            'project' => $project,
            'result' => null
        ]);
    }

    /**
     * Analyze the URL of a specific project and generate SEO meta tags.
     */
    public function analyze(Request $request)
    {
        $request->validate([
            'url' => 'required|string',
            'project_id' => 'nullable|exists:projects,id'
        ]);
    
        $url = $request->input('url');
        
        // Normalize URL - ensure it has a scheme
        $url = $this->normalizeUrl($url);
        
        $project = $request->has('project_id') ? Project::findOrFail($request->input('project_id')) : null;
    
        if ($project) {
            $this->authorize('view', $project);
        }
    
        try {
            // Use the HTTP client with minimal configuration
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 10,
                'http_errors' => false,
            ])
            ->withHeaders([
                'User-Agent' => 'Test Agent',
                'Accept' => 'text/html',
            ])
            ->get($url);
            
            if ($response->failed()) {
                throw new \Exception("Failed to fetch URL. Status code: " . $response->status());
            }
            
            $html = $response->body();
            
            // Simple check if we got HTML content
            if (empty($html) || !str_contains($html, '<html')) {
                throw new \Exception("Invalid HTML content received");
            }
            
            $crawler = new Crawler($html);
    
            $currentTitle = $crawler->filter('title')->count() ? 
                trim($crawler->filter('title')->first()->text()) : '';
                
            $currentDescription = $crawler->filter('meta[name="description"]')->count() ? 
                trim($crawler->filter('meta[name="description"]')->first()->attr('content')) : '';
    
            return response()->json([
                'title' => $currentTitle,
                'description' => $currentDescription,
                'status' => 'success'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to analyze URL: ' . $e->getMessage(),
                'status' => 'error',
                'url_attempted' => $url // For debugging
            ], 500);
        }
    }

    /**
     * Normalize URL to ensure consistent format
     */
    protected function normalizeUrl(string $url): string
    {
        // Remove any whitespace
        $url = trim($url);
        
        // Add scheme if missing
        if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
            $url = 'http://' . $url;
        }
        
        // Parse URL to components
        $parts = parse_url($url);
        
        // Rebuild URL with consistent format
        $scheme = isset($parts['scheme']) ? strtolower($parts['scheme']) . '://' : 'http://';
        $host = isset($parts['host']) ? strtolower($parts['host']) : '';
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        $path = $parts['path'] ?? '/';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';
        $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';
        
        // Remove www. for consistency
        $host = preg_replace('/^www\./i', '', $host);
        
        return $scheme . $host . $port . $path . $query . $fragment;
    }
}