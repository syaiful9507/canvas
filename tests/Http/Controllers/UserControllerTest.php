<?php

namespace Canvas\Tests\Http\Controllers;

use Canvas\Models\Post;
use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Uuid\Uuid;

/**
 * Class UserControllerTest.
 *
 * @covers \Canvas\Http\Controllers\UserController
 * @covers \Canvas\Http\Requests\StoreUserRequest
 * @covers \Canvas\Http\Middleware\VerifyAdmin
 */
class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAllUsersAreFetchedByDefault(): void
    {
        $admin = User::factory()->admin()->create();

        $editor = User::factory()->editor()->create();

        $contributor = User::factory()->contributor()->create();

        $response = $this->actingAs($admin, 'canvas')
            ->getJson(route('canvas.users.index'))
            ->assertJsonFragment([
                'id' => $admin->id,
                'id' => $editor->id,
                'id' => $contributor->id,
            ])
            ->assertSuccessful();

        $this->assertInstanceOf(User::class, $response->getOriginalContent()->first());

        $this->assertInstanceOf(LengthAwarePaginator::class, $response->getOriginalContent());

        $this->assertCount(3, $response->getOriginalContent());
    }

    public function testUsersCanBeSortedByCreationDateWithAGivenQueryParameter(): void
    {
        $admin = User::factory()->admin()->create(['created_at' => now()]);

        $oldUser = User::factory()->create(['created_at' => now()->subDay()]);

        $response = $this->actingAs($admin, 'canvas')
            ->getJson(route('canvas.users.index', ['sort' => 'desc']))
            ->assertSuccessful();

        $this->assertSame($response->getOriginalContent()->first()->id, $admin->id);

        $response = $this->actingAs($admin, 'canvas')
            ->getJson(route('canvas.users.index', ['sort' => 'asc']))
            ->assertSuccessful();

        $this->assertSame($response->getOriginalContent()->first()->id, $oldUser->id);

        $response = $this->actingAs($admin, 'canvas')
            ->getJson(route('canvas.users.index'))
            ->assertSuccessful();

        $this->assertSame($response->getOriginalContent()->first()->id, $admin->id);
    }

    public function testAdminsCanBeFetchedWithAGivenQueryParameter(): void
    {
        $admin = User::factory()->admin()->create();

        $editor = User::factory()->editor()->create();

        $contributor = User::factory()->contributor()->create();

        $this->actingAs($admin, 'canvas')
            ->getJson(route('canvas.users.index', ['role' => User::$admin_id]))
            ->assertSuccessful()
            ->assertJsonFragment([
                'id' => $admin->id,
            ])
            ->assertJsonMissing([
                'id' => $contributor->id,
                'id' => $editor->id,
            ]);
    }

    public function testEditorsCanBeFetchedWithAGivenQueryParameter(): void
    {
        $admin = User::factory()->admin()->create();

        $editor = User::factory()->editor()->create();

        $contributor = User::factory()->contributor()->create();

        $this->actingAs($admin, 'canvas')
            ->getJson(route('canvas.users.index', ['role' => User::$editor_id]))
            ->assertSuccessful()
            ->assertJsonFragment([
                'id' => $editor->id,
            ])
            ->assertJsonMissing([
                'id' => $admin->id,
                'id' => $contributor->id,
            ]);
    }

    public function testContributorsCanBeFetchedWithAGivenQueryParameter(): void
    {
        $admin = User::factory()->admin()->create();

        $editor = User::factory()->editor()->create();

        $contributor = User::factory()->contributor()->create();

        $this->actingAs($admin, 'canvas')
            ->getJson(route('canvas.users.index', ['role' => User::$contributor_id]))
            ->assertSuccessful()
            ->assertJsonFragment([
                'id' => $contributor->id,
            ])
            ->assertJsonMissing([
                'id' => $admin->id,
                'id' => $editor->id,
            ]);
    }

    public function testCreateDataForUser(): void
    {
        $response = $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.users.create'))
            ->assertSuccessful();

        $this->assertInstanceOf(User::class, $response->getOriginalContent());
    }

    public function testExistingUserData(): void
    {
        $admin = User::factory()->admin()->create();

        $contributor = User::factory()->contributor()->create();

        $response = $this->actingAs($admin, 'canvas')
                         ->getJson(route('canvas.users.show', ['id' => $contributor->id]))
                         ->assertSuccessful();

        $this->assertTrue($contributor->is($response->getOriginalContent()));
    }

    public function testListPostsForUser(): void
    {
        $user = User::factory()->admin()->has(Post::factory())->create();

        $response = $this->actingAs($user, 'canvas')
                         ->getJson(route('canvas.users.posts', ['id' => $user->id]))
                         ->assertSuccessful();

        $this->assertInstanceOf(Post::class, $response->getOriginalContent()->first());

        $this->assertInstanceOf(LengthAwarePaginator::class, $response->getOriginalContent());

        $this->assertCount(1, $response->getOriginalContent());
    }

    public function testUserNotFound(): void
    {
        $this->actingAs(User::factory()->admin()->create(), 'canvas')
             ->getJson(route('canvas.users.show', ['id' => 'not-a-user']))
             ->assertNotFound();
    }

    public function testStoreNewUser(): void
    {
        $data = [
            'id' => Uuid::uuid4()->toString(),
            'name' => 'Name',
            'email' => 'email@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->actingAs(User::factory()->admin()->create(), 'canvas')
                         ->putJson(route('canvas.users.store', ['id' => $data['id']]), $data)
                         ->assertSuccessful();

        $this->assertInstanceOf(User::class, $response->getOriginalContent());

        $this->assertSame($data['email'], $response->getOriginalContent()->email);
    }

    public function testUpdateExistingUser(): void
    {
        $contributor = User::factory()->contributor()->create();

        $data = [
            'id' => $contributor->id,
            'name' => 'New name',
            'email' => 'new-email@example.com',
        ];

        $response = $this->actingAs(User::factory()->admin()->create(), 'canvas')
                ->putJson(route('canvas.users.store', ['id' => $contributor->id]), $data)
                         ->assertSuccessful()
                         ->assertJsonFragment([
                             'id' => $contributor->id,
                             'name' => $data['name'],
                             'email' => $data['email'],
                         ]);

        $this->assertInstanceOf(User::class, $response->getOriginalContent());

        $this->assertSame($data['email'], $response->getOriginalContent()->email);
    }

    public function testInvalidPasswordCombinationsAreValidated(): void
    {
        $contributor = User::factory()->contributor()->create();

        $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->putJson(route('canvas.users.store', ['id' => $contributor->id]), [
                'id' => $contributor->id,
                'name' => $contributor->name,
                'email' => $contributor->email,
                'password' => 'password',
                'password_confirmation' => 'not-a-match',
            ])
             ->assertStatus(422)
             ->assertJsonStructure([
                 'errors' => [
                     'password',
                 ],
             ]);
    }

    public function testShortPasswordsAreValidated(): void
    {
        $contributor = User::factory()->contributor()->create();

        $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->putJson(route('canvas.users.store', ['id' => $contributor->id]), [
                'id' => $contributor->id,
                'name' => $contributor->name,
                'email' => $contributor->email,
                'password' => 'pass',
                'password_confirmation' => 'pass',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'password',
                ],
            ]);
    }

    public function testDuplicateUsernamesAreValidated(): void
    {
        $admin = User::factory()->admin()->create();

        $editor = User::factory()->editor()->create();

        $this->actingAs($admin, 'canvas')
             ->putJson(route('canvas.users.store', ['id' => $editor->id]), [
                 'id' => $editor->id,
                 'name' => $editor->name,
                 'email' => $editor->email,
                 'username' => $admin->username,
             ])
             ->assertStatus(422)
             ->assertJsonStructure([
                 'errors' => [
                     'username',
                 ],
             ]);
    }

    public function testDuplicateEmailsAreValidated(): void
    {
        $admin = User::factory()->admin()->create();

        $editor = User::factory()->editor()->create();

        $this->actingAs($admin, 'canvas')
            ->putJson(route('canvas.users.store', ['id' => $editor->id]), [
                'id' => $editor->id,
                'name' => $editor->name,
                'email' => $admin->email,
            ])
             ->assertStatus(422)
             ->assertJsonStructure([
                 'errors' => [
                     'email',
                 ],
             ]);
    }

    public function testInvalidEmailsAreValidated(): void
    {
        $contributor = User::factory()->contributor()->create();

        $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->putJson(route('canvas.users.store', ['id' => $contributor->id]), [
                'id' => $contributor->id,
                'name' => $contributor->name,
                'email' => 'not-an-email',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'email',
                ],
            ]);
    }

    public function testUsersCannotDeleteTheirOwnAccount(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin, 'canvas')
             ->deleteJson(route('canvas.users.destroy', ['id' => $admin]))
             ->assertForbidden();
    }

    public function testDeleteExistingUser(): void
    {
        $admin = User::factory()->admin()->create();

        $editor = User::factory()->editor()->create();

        $this->actingAs($admin, 'canvas')
             ->deleteJson(route('canvas.users.destroy', ['id' => 'not-a-user']))
             ->assertNotFound();

        $this->actingAs($admin, 'canvas')
            ->deleteJson(route('canvas.users.destroy', ['id' => $editor->id]))
             ->assertSuccessful()
             ->assertNoContent();

        $this->assertSoftDeleted('canvas_users', [
            'id' => $editor->id,
            'email' => $editor->email,
        ]);
    }
}
