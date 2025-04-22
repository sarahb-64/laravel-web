<?php

namespace App\Http\Controllers\Landing;

use App\Models\Landing\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class PortfolioController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return Inertia::render('Landing/Portfolio/Index', ['title' => 'Portafolio'], [
            'projects' => $projects
        ]);
    }

    public function show(Project $project)
    {
        return Inertia::render('Landing/Portfolio/Show', [
            'project' => $project
        ]);
    }
}