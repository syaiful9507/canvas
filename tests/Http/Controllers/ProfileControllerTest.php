<?php

namespace Canvas\Tests\Http\Controllers;

use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ProfileControllerTest.
 *
 * @covers \Canvas\Http\Controllers\ProfileController
 * @covers \Canvas\Http\Requests\StoreUserRequest
 */
class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanFetchTheCurrentProfile(): void
    {
        $response = $this->actingAs($user = User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.profile.show'))
            ->assertSuccessful();

        $this->assertInstanceOf(User::class, $response->getOriginalContent());

        $this->assertSame($user->email, $response->getOriginalContent()->email);
    }

    public function testItCanUpdateTheCurrentProfile(): void
    {
        $admin = User::factory()->admin()->create();

        $newName = 'New name';

        $newEmail = 'new-email@example.com';

        $response = $this->actingAs($admin, 'canvas')
            ->putJson(route('canvas.profile.update', ['id' => $admin->id]), [
                'id' => $admin->id,
                'name' => $newName,
                'email' => $newEmail,
            ])
            ->assertSuccessful()
            ->assertJsonFragment([
                'id' => $admin->id,
                'name' => $newName,
                'email' => $newEmail,
            ]);

        $this->assertArrayHasKey('i18n', $response);

        $this->assertArrayHasKey('user', $response);

        $this->assertInstanceOf(User::class, $response->getOriginalContent()['user']);

        $this->assertSame($newEmail, $response->getOriginalContent()['user']->email);
    }
}
