<?php

namespace Canvas\Tests\Http\Middleware;

use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class VerifyAdminTest.
 *
 * @covers \Canvas\Http\Middleware\VerifyPermission
 */
class VerifyPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanViewAnyUserProfile(): void
    {
        $admin = User::factory()->admin()->create();
        $contributor = User::factory()->contributor()->create();

        $this->actingAs($admin, 'canvas')
            ->getJson("canvas/api/users/{$contributor->id}")
            ->assertSuccessful();
    }

    public function testAdminCanListAnyUserPosts(): void
    {
        $admin = User::factory()->admin()->create();
        $editor = User::factory()->editor()->create();
        $contributor = User::factory()->contributor()->create();

        $this->actingAs($admin, 'canvas')
            ->getJson("canvas/api/users/{$contributor->id}/posts")
            ->assertSuccessful();

        $this->actingAs($admin, 'canvas')
            ->getJson("canvas/api/users/{$editor->id}/posts")
            ->assertSuccessful();
    }

    public function testAdminCanUpdateAnyUser(): void
    {
        $admin = User::factory()->admin()->create();
        $editor = User::factory()->editor()->create();
        $contributor = User::factory()->contributor()->create();

        $this->actingAs($admin, 'canvas')
            ->postJson("canvas/api/users/{$contributor->id}", $contributor->toArray())
            ->assertSuccessful();

        $this->actingAs($admin, 'canvas')
            ->postJson("canvas/api/users/{$editor->id}", $editor->toArray())
            ->assertSuccessful();
    }

    public function testEditorCanOnlyViewTheirUserProfile(): void
    {
        $editor = User::factory()->editor()->create();
        $contributor = User::factory()->contributor()->create();

        $this->actingAs($editor, 'canvas')
            ->getJson("canvas/api/users/{$contributor->id}")
            ->assertForbidden();

        $this->actingAs($editor, 'canvas')
            ->getJson("canvas/api/users/{$editor->id}")
            ->assertSuccessful();
    }

    public function testEditorCanOnlyListTheirUserPosts(): void
    {
        $editor = User::factory()->editor()->create();
        $contributor = User::factory()->contributor()->create();

        $this->actingAs($editor, 'canvas')
            ->getJson("canvas/api/users/{$contributor->id}/posts")
            ->assertForbidden();

        $this->actingAs($editor, 'canvas')
            ->getJson("canvas/api/users/{$editor->id}/posts")
            ->assertSuccessful();
    }

    public function testEditorCanOnlyUpdateTheirProfile(): void
    {
        $editor = User::factory()->editor()->create();
        $contributor = User::factory()->contributor()->create();

        $this->actingAs($editor, 'canvas')
            ->postJson("canvas/api/users/{$contributor->id}", $contributor->toArray())
            ->assertForbidden();

        $this->actingAs($editor, 'canvas')
            ->postJson("canvas/api/users/{$editor->id}", $editor->toArray())
            ->assertSuccessful();
    }

    public function testContributorCanOnlyViewTheirUserProfile(): void
    {
        $editor = User::factory()->editor()->create();
        $contributor = User::factory()->contributor()->create();

        $this->actingAs($contributor, 'canvas')
            ->getJson("canvas/api/users/{$editor->id}")
            ->assertForbidden();

        $this->actingAs($contributor, 'canvas')
            ->getJson("canvas/api/users/{$contributor->id}")
            ->assertSuccessful();
    }

    public function testContributorCanOnlyListTheirUserPosts(): void
    {
        $editor = User::factory()->editor()->create();
        $contributor = User::factory()->contributor()->create();

        $this->actingAs($contributor, 'canvas')
            ->getJson("canvas/api/users/{$editor->id}/posts")
            ->assertForbidden();

        $this->actingAs($contributor, 'canvas')
            ->getJson("canvas/api/users/{$contributor->id}/posts")
            ->assertSuccessful();
    }

    public function testContributorCanOnlyUpdateTheirProfile(): void
    {
        $editor = User::factory()->editor()->create();
        $contributor = User::factory()->contributor()->create();

        $this->actingAs($contributor, 'canvas')
            ->postJson("canvas/api/users/{$editor->id}", $editor->toArray())
            ->assertForbidden();

        $this->actingAs($contributor, 'canvas')
            ->postJson("canvas/api/users/{$contributor->id}", $contributor->toArray())
            ->assertSuccessful();
    }
}
