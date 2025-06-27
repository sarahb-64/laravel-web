<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenAI;

class SocialMediaController extends Controller
{
    /**
     * Display a list of projects to choose from.
     */
    public function index()
    {
        $projects = Auth::user()->projects;
        return view('social-media.index', compact('projects'));
    }

    /**
     * Show the post generation tool for a specific project.
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return view('social-media.show', ['project' => $project, 'posts' => null]);
    }

    /**
     * Generate social media posts for a specific project using AI.
     */
    public function generate(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        $request->validate([
            'topic' => 'required|string|max:255',
            'platform' => 'required|in:twitter,facebook,linkedin',
            'tone' => 'required|in:profesional,amigable,informativo',
        ]);

        $projectName = $project->name;
        $topic = $request->input('topic');
        $platform = $request->input('platform');
        $tone = $request->input('tone');

        try {
            $client = OpenAI::client(config('services.openai.secret'));

            $prompt = "Actúa como un Community Manager experto. Genera 3 posts para la red social '$platform' sobre el proyecto '$projectName'. El tema principal de los posts es: '$topic'. El tono debe ser '$tone'. Incluye hashtags relevantes. Formatea la salida como un array de JSON, donde cada objeto JSON tenga una única clave 'post_text'.";

            $result = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'temperature' => 0.8,
            ]);

            $generatedPosts = json_decode($result->choices[0]->message->content, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($generatedPosts)) {
                 throw new \Exception("La respuesta de la IA no es un JSON válido o no tiene el formato de array esperado.");
            }

            return view('social-media.show', [
                'project' => $project,
                'posts' => $generatedPosts,
                'topic' => $topic,
                'platform' => $platform,
                'tone' => $tone,
            ]);

        } catch (\Exception $e) {
            return redirect()->route('tools.social-media.show', $project)->with('error', 'No se pudieron generar los posts. Error: ' . $e->getMessage());
        }
    }
}
