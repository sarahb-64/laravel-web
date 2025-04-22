<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Seo\Project;
use App\Models\Seo\Keyword;
use Illuminate\Http\Request;

class SeoDashboardController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects()->withCount('keywords')->get();
        $keywords = auth()->user()->keywords()->latest()->take(10)->get();
        
        return Inertia::render('Seo/Dashboard', [
            'projects' => $projects,
            'keywords' => $keywords
        ]);
    }
}
