<?php

namespace Canvas\Tests\Http\Middleware;

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
        $this->actingAs($this->admin, 'canvas')
            ->getJson("canvas/api/users/{$this->contributor->id}")
            ->assertSuccessful();
    }

    public function testAdminCanListAnyUserPosts(): void
    {
        $this->actingAs($this->admin, 'canvas')
            ->getJson("canvas/api/users/{$this->contributor->id}/posts")
            ->assertSuccessful();

        $this->actingAs($this->admin, 'canvas')
            ->getJson("canvas/api/users/{$this->editor->id}/posts")
            ->assertSuccessful();
    }

    public function testAdminCanUpdateAnyUser(): void
    {
        $this->actingAs($this->admin, 'canvas')
            ->postJson("canvas/api/users/{$this->contributor->id}", $this->contributor->toArray())
            ->assertSuccessful();

        $this->actingAs($this->admin, 'canvas')
            ->postJson("canvas/api/users/{$this->editor->id}", $this->editor->toArray())
            ->assertSuccessful();
    }

    public function testEditorCanOnlyViewTheirUserProfile(): void
    {
        $this->actingAs($this->editor, 'canvas')
            ->getJson("canvas/api/users/{$this->contributor->id}")
            ->assertForbidden();

        $this->actingAs($this->editor, 'canvas')
            ->getJson("canvas/api/users/{$this->editor->id}")
            ->assertSuccessful();
    }

    public function testEditorCanOnlyListTheirUserPosts(): void
    {
        $this->actingAs($this->editor, 'canvas')
            ->getJson("canvas/api/users/{$this->contributor->id}/posts")
            ->assertForbidden();

        $this->actingAs($this->editor, 'canvas')
            ->getJson("canvas/api/users/{$this->editor->id}/posts")
            ->assertSuccessful();
    }

    public function testEditorCanOnlyUpdateTheirProfile(): void
    {
        $this->actingAs($this->editor, 'canvas')
            ->postJson("canvas/api/users/{$this->contributor->id}", $this->contributor->toArray())
            ->assertForbidden();

        $this->actingAs($this->editor, 'canvas')
            ->postJson("canvas/api/users/{$this->editor->id}", $this->editor->toArray())
            ->assertSuccessful();
    }

    public function testContributorCanOnlyViewTheirUserProfile(): void
    {
        $this->actingAs($this->contributor, 'canvas')
            ->getJson("canvas/api/users/{$this->editor->id}")
            ->assertForbidden();

        $this->actingAs($this->contributor, 'canvas')
            ->getJson("canvas/api/users/{$this->contributor->id}")
            ->assertSuccessful();
    }

    public function testContributorCanOnlyListTheirUserPosts(): void
    {
        $this->actingAs($this->contributor, 'canvas')
            ->getJson("canvas/api/users/{$this->editor->id}/posts")
            ->assertForbidden();

        $this->actingAs($this->contributor, 'canvas')
            ->getJson("canvas/api/users/{$this->contributor->id}/posts")
            ->assertSuccessful();
    }

    public function testContributorCanOnlyUpdateTheirProfile(): void
    {
        $this->actingAs($this->contributor, 'canvas')
            ->postJson("canvas/api/users/{$this->editor->id}", $this->editor->toArray())
            ->assertForbidden();

        $this->actingAs($this->contributor, 'canvas')
            ->postJson("canvas/api/users/{$this->contributor->id}", $this->contributor->toArray())
            ->assertSuccessful();
    }
}
