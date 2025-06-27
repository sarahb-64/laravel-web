<?php

namespace Tests\Feature\Seo;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Inertia\Testing\AssertableInertia;

class MetaTagsTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock the view to prevent Vite asset loading
        $this->withoutVite();
        $this->withoutMiddleware([
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        ]);
        
        // Create a test user
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_access_meta_tags_page()
    {
        $response = $this->get(route('seo.meta-tags.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function test_it_can_analyze_meta_tags()
    {
        // Set up the HTTP mock to respond to any URL
        Http::fake(function ($request) {
            dump('Intercepted request to:', $request->url());
            
            return Http::response(
                '<!DOCTYPE html><html><head>'. 
                '<title>Example Page</title>'. 
                '<meta name="description" content="This is an example page">'.
                '</head><body>Content</body></html>',
                200,
                ['Content-Type' => 'text/html']
            );
        });
    
        // Make the request to our endpoint
        $response = $this->postJson(route('seo.meta-tags.analyze'), [
            'url' => 'http://test.example.com'
        ]);
        
        // Debug the response if needed
        if ($response->status() !== 200) {
            dump('Response status:', $response->status());
            dump('Response content:', $response->content());
        }
    
        // Assert the response structure
        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Example Page',
                'description' => 'This is an example page',
                'status' => 'success'
            ]);
            
        // Verify the HTTP request was made to our test URL
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'test.example.com');
        });
    }
}