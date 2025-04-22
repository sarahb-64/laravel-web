<?php

namespace App\Http\Controllers\Seo;

use App\Services\DataForSEOService;
use App\Models\Seo\RankResult;
use App\Models\Keyword;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RankTrackerController extends Controller
{
    public function index()
    {
        return inertia('Seo/RankTracker/Index', [
            'keywords' => auth()->user()->keywords()->latest()->get()
        ]);
    }

    public function store(Request $request, DataForSEOService $dataForSEO)
    {
        $validated = $request->validate([
            'keywords' => 'required|array',
            'domain' => 'required|url',
            'language' => 'nullable|string',
            'location' => 'nullable|string'
        ]);

        $apiResult = $dataForSEO->fetchRankings(
            $validated['keywords'],
            $validated['domain'],
            $validated['language'] ?? 'es',
            $validated['location'] ?? 2840
        );

        // Procesar y guardar los resultados
        $tasks = $apiResult['tasks'] ?? [];
        $saved = [];

        foreach ($tasks as $task) {
            foreach ($task['result'] ?? [] as $result) {
                foreach ($result['items'] ?? [] as $item) {
                    $saved[] = RankResult::create([
                        'user_id' => auth()->id(),
                        'keyword_id' => Keyword::where('keyword', $item['keyword'])->first()->id,
                        'domain' => $validated['domain'],
                        'position' => $item['position'],
                        'search_volume' => $item['search_volume'] ?? null,
                        'competition' => $item['competition'] ?? null,
                        'cpc' => $item['cpc'] ?? null,
                        'location' => $validated['location'] ?? 'Spain'
                    ]);
                }
            }
        }

        return response()->json(['saved' => $saved]);
    }

    public function history(Request $request)
    {
        $keyword = $request->query('keyword');
        $domain = $request->query('domain');

        $results = RankResult::where('user_id', auth()->id())
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('keyword', function ($query) use ($keyword) {
                    $query->where('keyword', 'like', '%' . $keyword . '%');
                });
            })
            ->when($domain, function ($query) use ($domain) {
                $query->where('domain', 'like', '%' . $domain . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return inertia('Seo/RankTracker/History', [
            'results' => $results
        ]);
    }
}