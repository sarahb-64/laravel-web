<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Landing\Skill;
use Illuminate\Http\Request;

class SkillsController extends Controller
{
    public function show(Skill $skill)
    {
        return Inertia::render('Landing/Skill', [
            'skill' => $skill
        ]);
    }
}