<?php

namespace Tests\Feature\Seo;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use App\Http\Controllers\Seo\SeoAnalyzerController;
use App\Jobs\SeoAnalysisJob;

class SeoAnalyzerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        
        // Simular la respuesta HTTP
        Http::fake([
            '*' => Http::response([
                'title' => 'Test Title',
                'description' => 'Test Description',
                'url' => 'https://example.com'
            ], 200)
        ]);
        
        // Simular la cola
        Queue::fake();
    }

    public function test_seo_analysis_can_be_triggered()
    {
        $this->withoutExceptionHandling();
        
        $response = $this->post('/seo/analyze', [
            'url' => 'https://example.com'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'analysis_id'
            ]);

        // Verificar que el job se despachó
        Queue::assertPushed(SeoAnalysisJob::class);
    }
}