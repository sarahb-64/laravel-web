<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenAI;

class PaidAdsController extends Controller
{
    /**
     * Display a list of projects to choose from.
     */
    public function index()
    {
        $projects = Auth::user()->projects;
        return view('paid-ads.index', compact('projects'));
    }

    /**
     * Show the ad generation tool for a specific project.
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return view('paid-ads.show', ['project' => $project, 'ads' => null]);
    }

    /**
     * Generate ad copy for a specific project using AI.
     */
    public function generate(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        $request->validate([
            'keywords' => 'required|string|max:255',
            'platform' => 'required|in:google,facebook,linkedin',
        ]);

        $productDescription = $project->description;
        $keywords = $request->input('keywords');
        $platform = $request->input('platform');

        try {
            $client = OpenAI::client(config('services.openai.secret'));

            $prompt = "Eres un experto en marketing digital. Crea copys para un anuncio en '$platform' sobre un producto/servicio con la siguiente descripción: '$productDescription'. El anuncio debe enfocarse en estas palabras clave: '$keywords'. Genera 3 variantes del anuncio. Cada variante debe tener un titular (headline) de máximo 30 caracteres y un texto principal (body) de máximo 90 caracteres. Formatea la salida como un array de JSON, donde cada objeto JSON tenga las claves 'headline' y 'body'.";

            $result = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'temperature' => 0.7,
            ]);

            $generatedAds = json_decode($result->choices[0]->message->content, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($generatedAds)) {
                 throw new \Exception("La respuesta de la IA no es un JSON válido o no tiene el formato de array esperado.");
            }

            return view('paid-ads.show', [
                'project' => $project,
                'ads' => $generatedAds,
                'platform' => $platform,
                'keywords' => $keywords,
            ]);

        } catch (\Exception $e) {
            return redirect()->route('tools.paid-ads.show', $project)->with('error', 'No se pudo generar el anuncio. Error: ' . $e->getMessage());
        }
    }
}
