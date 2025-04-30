<?php

namespace Tests\Feature\Integration;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use App\Http\Controllers\Seo\SeoAnalyzerController;
use App\Http\Controllers\AbTestController;
use App\Http\Controllers\AnswerThePublicController;

class ToolIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        
        // Simular servicios externos
        Http::fake([
            '*' => Http::response([
                'title' => 'Test Title',
                'description' => 'Test Description',
                'url' => 'https://example.com'
            ], 200)
        ]);
        
        Queue::fake();
    }

    public function test_tools_can_be_used_together()
    {
        // 1. Realizar análisis SEO
        $response = $this->post('/seo/analyze', [
            'url' => 'https://example.com'
        ]);
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'analysis_id'
            ]);

        // 2. Realizar cálculo A/B Test
        $abTestResponse = $this->postJson('/ab-test/calculate', [
            '_token' => csrf_token(),
            'variant_a_conversions' => 100,
            'variant_a_visitors' => 1000,
            'variant_b_conversions' => 120,
            'variant_b_visitors' => 1000
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);

        // Verificar el estado y contenido de la respuesta
        $abTestResponse->assertStatus(200);

        // Imprimir la respuesta para depuración
        $content = $abTestResponse->getContent();
        echo "Contenido de la respuesta:\n";
        echo $content;
        echo "\n\n";

        // Verificar si es JSON válido
        $json = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error al decodificar JSON: " . json_last_error_msg() . "\n";
            echo "Contenido recibido: " . $content . "\n";
        }

        // Verificar la estructura del JSON
        $this->assertArrayHasKey('a_rate', $json);
        $this->assertArrayHasKey('b_rate', $json);
        $this->assertArrayHasKey('p_value', $json);
        $this->assertArrayHasKey('confidence_interval', $json);
        $this->assertArrayHasKey('is_significant', $json);

        // 3. Realizar búsqueda en AnswerThePublic
        $answerThePublicResponse = $this->post('/answer-the-public/suggestions', [
            'keyword' => 'test keyword'
        ]);

        // Verificar el estado y estructura de la respuesta
        $answerThePublicResponse->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);

        // 4. Verificar que todos los datos se guardan correctamente
        $this->assertDatabaseHas('seo_analyses', [
            'url' => 'https://example.com'
        ]);

        // 5. Verificar que podemos ver los resultados en el dashboard
        $dashboardResponse = $this->get('/seo/dashboard');
        $dashboardResponse->assertStatus(200);

        // Extraer el contenido del atributo data-page
        $html = $dashboardResponse->getContent();
        preg_match('/data-page="([^"]+)"/', $html, $matches);

        // Decodificar HTML entities y luego JSON
        $json = html_entity_decode($matches[1]); // convierte &quot; a "
        $data = json_decode($json, true);

        // Verificar que el componente es el correcto
        $this->assertEquals('Seo/Dashboard', $data['component']);
    }
}