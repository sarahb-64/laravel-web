<?php

namespace Tests\Feature\Seo;

use Tests\TestCase;
use App\Models\Seo\SeoProject;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeoDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_seo_dashboard()
    {
        $response = $this->get(route('seo.dashboard'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_shows_seo_overview()
    {
        $project = SeoProject::factory()->create();
        
        $response = $this->get(route('seo.dashboard.overview', $project->id));
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'project',
                        'keywords_count',
                        'average_position',
                        'top_keywords',
                        'ranking_trend',
                        'backlinks_count',
                        'last_updated'
                    ]
                ]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_project()
    {
        $response = $this->get(route('seo.dashboard.overview', 999));
        $response->assertStatus(404);
    }

    /** @test */
    public function it_shows_seo_insights()
    {
        $project = SeoProject::factory()->create();
        
        $response = $this->get(route('seo.dashboard.insights', $project->id));
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'top_opportunities',
                        'critical_issues',
                        'performance_metrics',
                        'content_suggestions'
                    ]
                ]);
    }
}
