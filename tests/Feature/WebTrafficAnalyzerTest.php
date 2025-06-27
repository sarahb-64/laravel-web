<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Mockery;
use App\Services\GoogleAnalyticsService;

class WebTrafficAnalyzerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock Google Analytics service
        $this->mockGoogleAnalyticsService();
    }

    protected function mockGoogleAnalyticsService()
    {
        $mock = Mockery::mock(GoogleAnalyticsService::class);
        $mock->shouldReceive('getAnalyticsData')
            ->andReturn([
                'sessions' => 1000,
                'users' => 800,
                'pageviews' => 5000,
                'bounceRate' => 45.5,
                'avgSessionDuration' => 120,
                'topPages' => [
                    ['page' => '/', 'views' => 1000],
                    ['page' => '/about', 'views' => 500],
                ],
                'trafficSources' => [
                    ['source' => 'google', 'sessions' => 400],
                    ['source' => 'direct', 'sessions' => 300],
                ],
                'devices' => [
                    ['device' => 'desktop', 'sessions' => 600],
                    ['device' => 'mobile', 'sessions' => 400],
                ]
            ]);
            
        $this->app->instance(GoogleAnalyticsService::class, $mock);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_web_traffic_analyzer()
    {
        $response = $this->get(route('web-traffic.index'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_users_can_view_web_traffic_analyzer()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->get(route('web-traffic.index'));
                         
        $response->assertStatus(200);
        $response->assertViewIs('WebTraffic/Index');
    }

    /** @test */
    public function can_analyze_web_traffic_for_project()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'user_id' => $user->id,
            'analytics_property_id' => 'UA-12345678-1'
        ]);

        $response = $this->actingAs($user)
                         ->post(route('web-traffic.analyze'), [
                             'project_id' => $project->id,
                             'start_date' => now()->subMonth()->format('Y-m-d'),
                             'end_date' => now()->format('Y-m-d')
                         ]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'sessions',
            'users',
            'pageviews',
            'bounceRate',
            'avgSessionDuration',
            'topPages' => [
                '*' => ['page', 'views']
            ],
            'trafficSources' => [
                '*' => ['source', 'sessions']
            ],
            'devices' => [
                '*' => ['device', 'sessions']
            ]
        ]);
    }

    /** @test */
    public function validates_required_fields_when_analyzing()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->post(route('web-traffic.analyze'), []);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'project_id',
            'start_date',
            'end_date'
        ]);
    }

    /** @test */
    public function user_can_only_analyze_their_own_projects()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $project = Project::factory()->create([
            'user_id' => $user1->id,
            'analytics_property_id' => 'UA-12345678-1'
        ]);

        $response = $this->actingAs($user2)
                         ->post(route('web-traffic.analyze'), [
                             'project_id' => $project->id,
                             'start_date' => now()->subMonth()->format('Y-m-d'),
                             'end_date' => now()->format('Y-m-d')
                         ]);
        
        $response->assertStatus(403);
    }

    /** @test */
    public function shows_error_when_analytics_service_fails()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'user_id' => $user->id,
            'analytics_property_id' => 'UA-12345678-1'
        ]);

        // Override the mock to simulate a failure
        $mock = Mockery::mock(GoogleAnalyticsService::class);
        $mock->shouldReceive('getAnalyticsData')
            ->andThrow(new \Exception('Analytics API error'));
            
        $this->app->instance(GoogleAnalyticsService::class, $mock);

        $response = $this->actingAs($user)
                         ->post(route('web-traffic.analyze'), [
                             'project_id' => $project->id,
                             'start_date' => now()->subMonth()->format('Y-m-d'),
                             'end_date' => now()->format('Y-m-d')
                         ]);
        
        $response->assertStatus(500);
        $response->assertJson([
            'error' => 'Error al analizar el tráfico web'
        ]);
    }
}
