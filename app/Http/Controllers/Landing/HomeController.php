<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Landing\Skill;
use App\Models\Landing\Service;
use App\Models\Landing\Project;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Landing/Home', [
            'skills' => Skill::all(),
            'services' => Service::all(),
            'projects' => Project::latest()->take(6)->get()
        ]);
    }
}