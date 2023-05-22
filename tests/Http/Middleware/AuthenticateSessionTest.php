<?php

namespace Canvas\Tests\Http\Middleware;

use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class AuthenticateSessionTest.
 *
 * @covers \Canvas\Http\Middleware\AuthenticateSession
 */
class AuthenticateSessionTest extends TestCase
{
    use RefreshDatabase;

    public function testUnauthenticatedUsersAreRedirectedToLogin(): void
    {
        $this->assertGuest()
            ->get('canvas/api')
            ->assertRedirect(route('canvas.login'));
    }

    public function testAuthenticatedUsersAreRedirectedToCanvas(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin, 'canvas')
            ->get(route('canvas.login'))
            ->assertRedirect(config('canvas.path'));

        $this->actingAs($admin, 'canvas')
            ->get('canvas/api')
            ->assertSuccessful();
    }
}
