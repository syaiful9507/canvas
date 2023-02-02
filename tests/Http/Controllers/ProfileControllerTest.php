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
        $data = [
            'name' => 'New name',
            'email' => 'new-email@example.com',
        ];

        $response = $this->actingAs($user = User::factory()->admin()->create(), 'canvas')
            ->putJson(route('canvas.profile.update'), $data)
            ->assertSuccessful()
            ->assertJsonFragment([
                'id' => $user->id,
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

        $this->assertArrayHasKey('i18n', $response);

        $this->assertArrayHasKey('user', $response);

        $this->assertInstanceOf(User::class, $response->getOriginalContent()['user']);

        $this->assertSame($data['email'], $response->getOriginalContent()['user']->email);
    }
}
