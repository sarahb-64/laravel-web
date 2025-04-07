<?php

namespace App\Http\Controllers;

use App\Services\GooglePositionService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $googlePositionService;

    public function __construct(GooglePositionService $googlePositionService)
    {
        $this->googlePositionService = $googlePositionService;
    }

    public function index()
    {
        return view('search');
    }

    public function trackPosition(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:255',
        ]);

        $keyword = $request->input('keyword');

        // Obtener la posición de la palabra clave usando el servicio
        $position = $this->googlePositionService->getPosition($keyword);

        if ($position) {
            return view('search', ['position' => $position, 'keyword' => $keyword]);
        }

        return redirect()->back()->with('error', 'No se pudo obtener la posición para la palabra clave.');
    }

    public function dashboard(Request $request)
    {
        $keywords = Keyword::all();
        $selectedKeyword = null;
        $dates = [];
        $positions = [];

        if ($request->has('keyword')) {
            $selectedKeyword = Keyword::findOrFail($request->keyword);
            $history = $selectedKeyword->positionHistories()
                ->orderBy('created_at')
                ->get();

            foreach ($history as $entry) {
                $dates[] = $entry->created_at->format('Y-m-d');
                $positions[] = $entry->position;
            }
        }

        return view('dashboard', compact('keywords', 'selectedKeyword', 'dates', 'positions'));
    }
}