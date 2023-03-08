<?php

namespace Canvas\Tests\Http\Requests;

use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ShowUserRequestTest.
 *
 * @covers \Canvas\Http\Requests\ShowUserRequest
 */
class ShowUserRequestTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminRoleOrShowingSelfIsRequired(): void
    {
        $user = User::factory()->create();

        $admin = User::factory()->admin()->create();

        $editor = User::factory()->editor()->create();

        $contributor = User::factory()->contributor()->create();

        $this->actingAs($contributor, 'canvas')
            ->getJson(route('canvas.users.show', ['id' => $user->id]))
            ->assertForbidden();

        $this->actingAs($contributor, 'canvas')
            ->getJson(route('canvas.users.show', ['id' => $contributor->id]))
            ->assertSuccessful();

        $this->actingAs($editor, 'canvas')
            ->getJson(route('canvas.users.show', ['id' => $user->id]))
            ->assertForbidden();

        $this->actingAs($admin, 'canvas')
            ->getJson(route('canvas.users.show', ['id' => $user->id]))
            ->assertSuccessful();
    }
}
