<?php

namespace Canvas\Tests\Http\Middleware;

use Canvas\Http\Middleware\ExpireTrafficInSession;
use Canvas\Models\Post;
use Canvas\Models\View;
use Canvas\Models\Visit;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

/**
 * Class ExpireTrafficInSessionTest.
 *
 * @covers \Canvas\Http\Middleware\ExpireTrafficInSession
 */
class ExpireTrafficInSessionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Set up a test route protected by the middleware
        Route::middleware([ExpireTrafficInSession::class])->any('/_test/session', function () {
            return true;
        });
    }

    public function testOldVisitsArePrunedFromSession(): void
    {
        $recent = Post::factory()->published()->has(Visit::factory()->set('created_at', now()))->create();
        $old = Post::factory()->published()->has(Visit::factory()->set('created_at', now()->subDay()))->create();

        session()->put('canvas.visited_posts.'.$recent->id, [
            'timestamp' => now()->timestamp,
            'ip' => '127.0.0.1',
        ]);

        session()->put('canvas.visited_posts.'.$old->id, [
            'timestamp' => now()->subDay()->timestamp,
            'ip' => '127.0.0.1',
        ]);

        $this->get('/_test/session')
             ->assertSessionHas("canvas.visited_posts.{$recent->id}")
             ->assertSessionMissing("canvas.visited_posts.{$old->id}");
    }

    public function testOldViewsArePrunedFromSession(): void
    {
        $recent = Post::factory()->published()->has(View::factory()->set('created_at', now()))->create();
        $old = Post::factory()->published()->has(View::factory()->set('created_at', now()->subDay()))->create();

        session()->put('canvas.viewed_posts.'.$recent->id, now()->timestamp);
        session()->put('canvas.viewed_posts.'.$old->id, now()->subHours(2)->timestamp);

        $this->get('/_test/session')
             ->assertSessionHas("canvas.viewed_posts.{$recent->id}")
             ->assertSessionMissing("canvas.viewed_posts.{$old->id}");
    }
}
