<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(401);
        }

        $projects = $user->projects()->withCount('keywords')->get();
        $keywords = $user->keywords()->latest()->take(10)->get();
        
        return Inertia::render('Seo/Dashboard', [
            'projects' => $projects,
            'keywords' => $keywords
        ]);
    }
}