<?php

namespace Tests\Feature\Seo;

use Tests\TestCase;
use App\Models\Seo\SeoProject;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeoProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_seo_projects()
    {
        SeoProject::factory()->count(3)->create();
        
        $response = $this->get(route('seo.projects.index'));
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'domain',
                            'created_at'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function it_can_create_seo_project()
    {
        $projectData = [
            'name' => 'Test Project',
            'domain' => 'test.com',
            'description' => 'Test description',
            'keywords' => ['test', 'seo', 'project']
        ];

        $response = $this->postJson(route('seo.projects.store'), $projectData);
        
        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'name',
                        'domain',
                        'description',
                        'keywords',
                        'created_at'
                    ]
                ]);

        $this->assertDatabaseHas('seo_projects', [
            'name' => 'Test Project',
            'domain' => 'test.com'
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->postJson(route('seo.projects.store'), []);
        
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'domain']);
    }

    /** @test */
    public function it_can_show_seo_project()
    {
        $project = SeoProject::factory()->create();
        
        $response = $this->get(route('seo.projects.show', $project->id));
        
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'id' => $project->id,
                        'name' => $project->name,
                        'domain' => $project->domain
                    ]
                ]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_project()
    {
        $response = $this->get(route('seo.projects.show', 999));
        $response->assertStatus(404);
    }
}
