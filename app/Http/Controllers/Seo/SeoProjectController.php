<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use App\Models\Seo\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeoProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Project::class, 'project');
    }

    public function index()
    {
        $projects = auth()->user()->projects()->latest()->get();
        return Inertia::render('Seo/Projects/Index', [
            'projects' => $projects
        ]);
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return Inertia::render('Seo/Projects/Show', [
            'project' => $project->load('keywords')
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|unique:seo_projects',
            'description' => 'nullable|string|min:10'
        ]);

        try {
            $project = auth()->user()->projects()->create($validated);
            return redirect()->route('seo.projects.index')->with('success', 'Proyecto creado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el proyecto')->withInput();
        }
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|unique:seo_projects,url,' . $project->id,
            'description' => 'nullable|string|min:10'
        ]);

        try {
            $project->update($validated);
            return redirect()->route('seo.projects.index')->with('success', 'Proyecto actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el proyecto')->withInput();
        }
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        try {
            $project->delete();
            return redirect()->route('seo.projects.index')->with('success', 'Proyecto eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el proyecto');
    }
}

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return Inertia::render('Seo/Projects/Edit', [
            'project' => $project
        ]);
    }

    public function stats(Project $project)
    {
        $this->authorize('view', $project);

        $stats = [
            'keywords_count' => $project->keywords()->count(),
            'average_difficulty' => $project->keywords()->avg('difficulty'),
            'search_volume' => $project->keywords()->sum('search_volume')
        ];

        return Inertia::render('Seo/Projects/Stats', [
            'project' => $project,
            'stats' => $stats
        ]);
    }

    public function history(Project $project)
    {
        $this->authorize('view', $project);
        
        $history = $project->activities()->latest()->paginate(10);
        
        return Inertia::render('Seo/Projects/History', [
            'project' => $project,
            'history' => $history
        ]);
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,xlsx|max:1024',
            'project_id' => 'required|exists:seo_projects,id'
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $this->authorize('update', $project);

        try {
            Excel::import(new ProjectImport($project), $request->file('file'));
            return redirect()->route('seo.projects.show', $project)->with('success', 'Datos importados exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al importar datos: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:seo_projects,id'
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $this->authorize('view', $project);

        return Excel::download(new ProjectExport($project), 'project-' . $project->id . '.xlsx');
    }
}