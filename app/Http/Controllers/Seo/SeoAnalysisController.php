<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class SeoAnalysisController extends Controller
{
    public function analyzeMetaTags(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        try {
            $client = new Client([
                'verify' => false,
                'timeout' => 30,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ],
            ]);

            $response = $client->get($request->url);
            $html = (string) $response->getBody();
            $crawler = new Crawler($html, $request->url);

            $result = [
                'url' => $request->url,
                'title' => $this->getTitle($crawler),
                'meta_description' => $this->getMetaTag($crawler, 'meta[name="description"]'),
                'meta_keywords' => $this->getMetaTag($crawler, 'meta[name="keywords"]'),
                'headings' => $this->getHeadings($crawler),
                'suggestions' => []
            ];

            $result['suggestions'] = $this->generateSuggestions($result);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al analizar la URL: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getTitle(Crawler $crawler)
    {
        $title = $crawler->filter('title')->first();
        return $title->count() > 0 ? $title->text() : '';
    }

    private function getMetaTag(Crawler $crawler, $selector)
    {
        $tag = $crawler->filter($selector)->first();
        return $tag->count() > 0 ? $tag->attr('content') : '';
    }

    private function getHeadings(Crawler $crawler)
    {
        $headings = [];
        for ($i = 1; $i <= 6; $i++) {
            $headings["h$i"] = $crawler->filter("h$i")->each(function (Crawler $node) {
                return $node->text();
            });
        }
        return $headings;
    }

    private function generateSuggestions($data)
    {
        $suggestions = [];
        
        // Sugerencias para el título
        $titleLength = mb_strlen($data['title'] ?? '');
        if ($titleLength === 0) {
            $suggestions[] = 'Añade un título a la página.';
        } elseif ($titleLength < 30) {
            $suggestions[] = 'El título es demasiado corto (mínimo recomendado: 30 caracteres).';
        } elseif ($titleLength > 60) {
            $suggestions[] = 'El título es demasiado largo (máximo recomendado: 60 caracteres).';
        }

        // Sugerencias para la meta descripción
        $descLength = mb_strlen($data['meta_description'] ?? '');
        if ($descLength === 0) {
            $suggestions[] = 'Añade una meta descripción.';
        } elseif ($descLength < 120) {
            $suggestions[] = 'La meta descripción es corta (recomendado: 120-160 caracteres).';
        } elseif ($descLength > 160) {
            $suggestions[] = 'La meta descripción es demasiado larga (recomendado: 120-160 caracteres).';
        }

        // Sugerencias para encabezados
        if (empty($data['headings']['h1'])) {
            $suggestions[] = 'Añade al menos un encabezado H1.';
        } elseif (count($data['headings']['h1']) > 1) {
            $suggestions[] = 'Solo debería haber un encabezado H1 por página.';
        }

        return $suggestions;
    }
}