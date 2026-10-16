<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FactoryStudioTest extends TestCase
{
    use RefreshDatabase;

    public function test_posts_index_page_loads_successfully_with_studio(): void
    {
        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertSee('Fake Data Studio');
        $response->assertSee('Tinker Playground');
        $response->assertSee('Interactive Factory Generator Studio');
        $response->assertSee('Web Tinker Console');
        $response->assertSee('Visual Analytics');
    }

    public function test_batch_data_generator_creates_posts_with_custom_states(): void
    {
        $response = $this->post(route('posts.generate'), [
            'count' => 20,
            'state' => 'published',
            'category' => 'Technology',
            'date_range' => 'past_month',
        ]);

        $response->assertRedirect(route('posts.index'));
        $response->assertSessionHas('success');

        $this->assertEquals(20, Post::count());
        $this->assertEquals(20, Post::where('is_published', true)->count());
        $this->assertEquals(20, Post::where('category', 'Technology')->count());
    }

    public function test_tinker_sandbox_evaluates_eloquent_queries(): void
    {
        Post::factory()->count(5)->published()->create();

        $response = $this->postJson(route('posts.tinker'), [
            'code' => 'Post::count();',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'result' => 5,
        ]);
        $response->assertJsonStructure([
            'success',
            'result',
            'execution_time_ms',
            'queries_count',
        ]);
    }

    public function test_tinker_sandbox_blocks_blacklisted_dangerous_functions(): void
    {
        $response = $this->postJson(route('posts.tinker'), [
            'code' => 'exec("whoami");',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('Security Violation', $response->json('error'));
    }

    public function test_database_reset_to_baseline(): void
    {
        Post::factory()->count(15)->create();

        $response = $this->post(route('posts.reset'));

        $response->assertRedirect(route('posts.index'));
        $response->assertSessionHas('success');

        $this->assertEquals(10, Post::count());
        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
        ]);
    }

    public function test_database_purge_fake_posts(): void
    {
        Post::factory()->count(10)->create();

        $response = $this->post(route('posts.purge'));

        $response->assertRedirect(route('posts.index'));
        $response->assertSessionHas('success');

        $this->assertEquals(0, Post::count());
    }

    public function test_analytics_api_returns_chart_data(): void
    {
        Post::factory()->count(10)->create();

        $response = $this->get(route('posts.analytics'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status_chart' => ['labels', 'data'],
            'category_chart' => ['labels', 'data'],
            'views_histogram' => ['labels', 'data'],
            'timeline_chart' => ['labels', 'data'],
            'author_leaderboard',
        ]);
    }

    public function test_print_export_view_renders(): void
    {
        Post::factory()->count(5)->create();

        $response = $this->get(route('posts.export.print'));

        $response->assertStatus(200);
        $response->assertSee('Posts Catalog &amp; Analytics Report', false);
        $response->assertSee('Print / Save as PDF');
    }
}
