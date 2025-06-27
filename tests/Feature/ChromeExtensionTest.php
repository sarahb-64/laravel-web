<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ApiToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class ChromeExtensionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function extension_can_authenticate_with_valid_token()
    {
        $user = User::factory()->create();
        $token = ApiToken::factory()->create([
            'user_id' => $user->id,
            'name' => 'Chrome Extension',
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => ['extension:analyze'],
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $plainTextToken,
            'Accept' => 'application/json',
        ])->getJson('/api/user');

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
        ]);
    }

    /** @test */
    public function extension_cannot_authenticate_with_invalid_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid-token',
            'Accept' => 'application/json',
        ])->getJson('/api/user');

        $response->assertStatus(401);
    }

    /** @test */
    public function extension_can_analyze_url()
    {
        $user = User::factory()->create();
        $token = ApiToken::factory()->create([
            'user_id' => $user->id,
            'name' => 'Chrome Extension',
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => ['extension:analyze'],
        ]);

        $url = 'https://example.com';
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $plainTextToken,
            'Accept' => 'application/json',
        ])->postJson('/api/extension/analyze', [
            'url' => $url,
            'html' => '<html><head><title>Test Page</title><meta name="description" content="Test Description"></head><body>Test Content</body></html>'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'meta' => [
                'title',
                'description',
                'keywords',
                'og:title',
                'og:description',
                'og:image',
                'og:type',
                'og:url',
            ],
            'headings' => [
                'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
            ],
            'images' => [
                '*' => ['src', 'alt']
            ],
            'links' => [
                '*' => ['href', 'text', 'nofollow', 'external']
            ],
            'seo' => [
                'title_length',
                'description_length',
                'has_favicon',
                'has_canonical',
                'has_robots',
                'has_sitemap'
            ]
        ]);
    }

    
    /** @test */
    public function extension_analyze_requires_url_parameter()
    {
        $user = User::factory()->create();
        $token = ApiToken::factory()->create([
            'user_id' => $user->id,
            'name' => 'Chrome Extension',
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => ['extension:analyze'],
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $plainTextToken,
            'Accept' => 'application/json',
        ])->postJson('/api/extension/analyze', [
            'html' => '<html></html>'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['url']);
    }
    
    /** @test */
    public function extension_can_save_analysis()
    {
        $user = User::factory()->create();
        $token = ApiToken::factory()->create([
            'user_id' => $user->id,
            'name' => 'Chrome Extension',
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => ['extension:analyze', 'extension:save'],
        ]);

        $url = 'https://example.com';
        $analysisData = [
            'url' => $url,
            'meta' => [
                'title' => 'Test Page',
                'description' => 'Test Description',
                'keywords' => 'test, example',
            ],
            'headings' => [
                'h1' => ['Test Heading'],
                'h2' => ['Subheading 1', 'Subheading 2'],
            ],
            'seo' => [
                'title_length' => 25,
                'description_length' => 150,
                'has_favicon' => true,
                'has_canonical' => true,
                'has_robots' => true,
                'has_sitemap' => false,
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $plainTextToken,
            'Accept' => 'application/json',
        ])->postJson('/api/extension/save', $analysisData);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Analysis saved successfully',
            'url' => $url
        ]);
        
        $this->assertDatabaseHas('seo_analyses', [
            'url' => $url,
            'user_id' => $user->id
        ]);
    }
    
    /** @test */
    public function extension_cannot_save_without_permission()
    {
        $user = User::factory()->create();
        $token = ApiToken::factory()->create([
            'user_id' => $user->id,
            'name' => 'Chrome Extension',
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => ['extension:analyze'], // Missing extension:save permission
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $plainTextToken,
            'Accept' => 'application/json',
        ])->postJson('/api/extension/save', [
            'url' => 'https://example.com',
            'meta' => []
        ]);

        $response->assertStatus(403);
    }
}
