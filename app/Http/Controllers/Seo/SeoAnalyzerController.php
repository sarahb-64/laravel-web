<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use App\Jobs\SeoAnalysisJob;
use App\Models\Seo\SeoAnalysis;
use Illuminate\Http\Request;

class SeoAnalyzerController extends Controller
{
    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
        ]);

        try {
            $analysis = SeoAnalysis::create([
                'url' => $validated['url']
            ]);
            SeoAnalysisJob::dispatch($validated['url'], $analysis->id);
            
            return response()->json([
                'message' => 'SEO analysis started',
                'analysis_id' => $analysis->id
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] === 1062) { // Código de error para duplicado
                $existingAnalysis = SeoAnalysis::where('url', $validated['url'])->first();
                return response()->json([
                    'message' => 'Analysis already exists',
                    'analysis_id' => $existingAnalysis->id
                ], 409);
            }
            throw $e;
        }
    }

    public function getStatus($id)
    {
        $analysis = SeoAnalysis::findOrFail($id);
        return response()->json($analysis->toArray());
    }
}