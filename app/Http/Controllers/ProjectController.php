<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the user's projects.
     */
    public function index()
    {
        $projects = Auth::user()->projects()->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,in_progress,completed',
            'analytics_property_id' => 'nullable|string|max:255',
        ]);

        // The user_id is automatically added by the Project model's booted method.
        Project::create($request->all());

        return redirect()->route('projects.index')
                         ->with('success', 'Proyecto creado con éxito.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        // Ensure the user can only see their own projects
        $this->authorize('view', $project);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,in_progress,completed',
            'analytics_property_id' => 'nullable|string|max:255',
        ]);

        $project->update($request->all());

        return redirect()->route('projects.index')
                         ->with('success', 'Proyecto actualizado con éxito.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();

        return redirect()->route('projects.index')
                         ->with('success', 'Proyecto eliminado con éxito.');
    }
}
