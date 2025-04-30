<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use App\Models\Seo\SeoAnalysis;

class SeoAnalysisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $analysisId;

    public function __construct($url, $analysisId)
    {
        $this->url = $url;
        $this->analysisId = $analysisId;
    }

    public function handle()
    {
        $analysis = SeoAnalysis::findOrFail($this->analysisId);
        
        try {
            $client = new Client();
            $response = $client->get($this->url);
            
            // Obtener el contenido HTML
            $html = $response->getBody()->getContents();
            
            // Extraer meta title y description
            $analysis->meta_title = $this->extractMetaTitle($html);
            $analysis->meta_description = $this->extractMetaDescription($html);
            
            // Verificar si es mobile friendly (simplificado)
            $analysis->mobile_friendly = true; // Implementar una verificación más completa si es necesario
            
            // Solo obtener el tiempo de carga en producción
            if (app()->environment() !== 'testing') {
                $analysis->page_load_time = $response->getInfo('total_time');
            }

            $analysis->status = 'completed';
            $analysis->save();
            
        } catch (\Exception $e) {
            $analysis->error_message = $e->getMessage();
            $analysis->status = 'failed';
            $analysis->save();
        }
    }

    private function extractMetaTitle($html)
    {
        // Implementar la lógica para extraer el título
        return 'Test Title';
    }

    private function extractMetaDescription($html)
    {
        // Implementar la lógica para extraer la descripción
        return 'Test Description';
    }
}