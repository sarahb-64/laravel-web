<?php

namespace Tests\Feature\Seo;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class OpenGraphTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $project;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Use our test layout
        View::addNamespace('test', resource_path('views'));
        View::share('layout', 'test-layout');
        
        // Disable model events to prevent the booted method from running
        Project::unsetEventDispatcher();
        
        // Create a user
        $this->user = User::factory()->create();
        
        // Create a project for the user
        $this->project = Project::create([
            'user_id' => $this->user->id,
            'name' => 'Test Project',
            'description' => 'Test Description',
            'url' => 'https://example.com',
            'analytics_property_id' => 'test-analytics-id',
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'status' => 'active'
        ]);
        
        // Mock HTTP client for the website
        Http::fake([
            'example.com' => Http::response('<html><head><title>Test</title><meta property="og:title" content="Test Title"><meta property="og:description" content="Test Description"></head><body>Test</body></html>', 200),
        ]);
        
        // Mock OpenAI API response
        $this->mockOpenAiResponse();
    }
    
    protected function mockOpenAiResponse()
    {
        $mockResponse = [
            'id' => 'chatcmpl-123',
            'object' => 'chat.completion',
            'created' => 1677652288,
            'model' => 'gpt-3.5-turbo-0613',
            'choices' => [[
                'index' => 0,
                'message' => [
                    'role' => 'assistant',
                    'content' => json_encode([
                        'og:title' => 'AI Suggested Title',
                        'og:description' => 'AI Suggested Description',
                        'og:image' => 'https://example.com/ai-image.jpg',
                    ])
                ],
                'finish_reason' => 'stop'
            ]],
            'usage' => [
                'prompt_tokens' => 9,
                'completion_tokens' => 12,
                'total_tokens' => 21
            ]
        ];

        // Mock the OpenAI API endpoint
        Http::fake([
            'api.openai.com/*' => Http::response($mockResponse, 200)
        ]);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_open_graph_tool()
    {
        // Make sure we're not authenticated
        $this->app['auth']->logout();
        
        $response = $this->get(route('seo.open-graph'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_users_can_view_open_graph_tool()
    {
        $this->withoutExceptionHandling();
        
        $response = $this->actingAs($this->user)
            ->get(route('seo.open-graph'));
            
        $response->assertStatus(200);
        $response->assertViewIs('seo.open-graph');
        $response->assertViewHas('projects');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}