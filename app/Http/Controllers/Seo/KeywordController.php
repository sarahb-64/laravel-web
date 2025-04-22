<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use App\Models\Seo\Keyword;
use Illuminate\Http\Request;
use App\Services\DataForSEOService;
use App\Models\Seo\RankResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KeywordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $keywords = Keyword::where('user_id', Auth::id())
            ->with(['rankResults' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->get();

        return inertia('Seo/Keywords/Index', [
            'keywords' => $keywords
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

        $keywords = $validated['keywords'];
        $domain = $validated['domain'];
        $language = $validated['language'] ?? 'es';
        $location = $validated['location'] ?? 2840;

        // Obtener datos de DataForSEO
        $apiResult = $dataForSEO->fetchKeywordData($keywords);

        // Procesar y guardar los datos
        $tasks = $apiResult['tasks'] ?? [];
        $saved = [];

        foreach ($tasks as $task) {
            foreach ($task['result'] ?? [] as $result) {
                foreach ($result['items'] ?? [] as $item) {
                    $keyword = Keyword::updateOrCreate(
                        [
                            'user_id' => Auth::id(),
                            'keyword' => $item['keyword']
                        ],
                        [
                            'search_volume' => $item['search_volume'] ?? null,
                            'competition' => $item['competition'] ?? null,
                            'cpc' => $item['cpc'] ?? null,
                            'domain' => $domain
                        ]
                    );

                    $saved[] = $keyword;
                }
            }
        }

        // Ahora obtener posiciones
        $rankings = $dataForSEO->fetchRankings($keywords, $domain, $language, $location);

        $rankTasks = $rankings['tasks'] ?? [];
        foreach ($rankTasks as $task) {
            foreach ($task['result'] ?? [] as $result) {
                foreach ($result['items'] ?? [] as $item) {
                    $keyword = Keyword::where('user_id', Auth::id())
                        ->where('keyword', $item['keyword'])
                        ->first();

                    if ($keyword) {
                        RankResult::create([
                            'user_id' => Auth::id(),
                            'keyword_id' => $keyword->id,
                            'domain' => $domain,
                            'position' => $item['position'],
                            'search_engine' => 'google',
                            'location' => $location
                        ]);
                    }
                }
            }
        }

        return inertia('Seo/Keywords/Index', [
            'keywords' => $saved,
            'message' => 'Datos actualizados correctamente'
        ]);
    }

    public function show($id)
    {
        $keyword = Keyword::where('user_id', Auth::id())
            ->with(['rankResults' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->findOrFail($id);

        return inertia('Seo/Keywords/Show', [
            'keyword' => $keyword
        ]);
    }

    public function destroy($id)
    {
        $keyword = Keyword::where('user_id', Auth::id())
            ->findOrFail($id);

        $keyword->delete();

        return redirect()->route('seo.keywords.index')
            ->with('success', 'Keyword eliminada correctamente');
    }

    public function keywordData(Request $request)
    {
        $keywords = $request->input('keywords', ['laravel']); // Puedes recibirlo del front

        $response = Http::withBasicAuth(
            env('DATAFORSEO_LOGIN'),
            env('DATAFORSEO_PASSWORD')
        )->post('https://api.dataforseo.com/v3/keywords_data/google_ads/search_volume/task_post',
            [
                ["keywords"=> $keywords, 
                "language_code"=> "es", 
                "location_code"=> 2840 //España
                ]
            ]
        );

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Error al consultar DataForSEO'], 500);
        }
    }
}