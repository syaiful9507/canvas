<?php

namespace Canvas\Tests\Http\Requests;

use Canvas\Models\Tag;
use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;

/**
 * Class StoreTagRequestTest.
 *
 * @covers \Canvas\Http\Requests\StoreTagRequest
 */
class StoreTagRequestTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminRoleIsRequired(): void
    {
        $this->actingAs($user = User::factory()->contributor()->create(), 'canvas')
            ->putJson(route('canvas.tags.store', ['id' => Uuid::uuid4()->toString()]), [
                'name' => 'A new tag',
                'slug' => 'a-new-tag',
                'user_id' => $user->id,
            ])
            ->assertForbidden();

        $this->actingAs($user = User::factory()->editor()->create(), 'canvas')
            ->putJson(route('canvas.tags.store', ['id' => Uuid::uuid4()->toString()]), [
                'name' => 'A new tag',
                'slug' => 'a-new-tag',
                'user_id' => $user->id,
            ])
            ->assertForbidden();

        $this->actingAs($user = User::factory()->admin()->create(), 'canvas')
            ->putJson(route('canvas.tags.store', ['id' => Uuid::uuid4()->toString()]), [
                'name' => 'A new tag',
                'slug' => 'a-new-tag',
                'user_id' => $user->id,
            ])
            ->assertSuccessful();
    }

    public function testSlugIsRequired(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->actingAs($tag->user, 'canvas')
            ->putJson(route('canvas.tags.store', ['id' => $tag->id]), [
                'name' => $tag->name,
                'user_id' => $tag->user->id,
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'slug',
                ],
            ]);

        $this->assertSame(trans('canvas::app.slug_required'), $response->getOriginalContent()['message']);
    }

    public function testSlugMustBeAlphaDash(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->actingAs($tag->user, 'canvas')
            ->putJson(route('canvas.tags.store', ['id' => $tag->id]), [
                'slug' => 'a new.slug',
                'name' => $tag->name,
                'user_id' => $tag->user->id,
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'slug',
                ],
            ]);

        $this->assertSame(trans('canvas::app.slug_alpha_dash'), $response->getOriginalContent()['message']);
    }

    public function testTagsCanShareTheSameSlugWithUniqueUsers(): void
    {
        $primaryAdmin = User::factory()->admin()->create();

        $response = $this->actingAs($primaryAdmin, 'canvas')
            ->putJson(route('canvas.tags.store', ['id' => Uuid::uuid4()->toString()]), [
                'name' => 'A new tag',
                'slug' => 'a-new-tag',
                'user_id' => $primaryAdmin->id,
            ]);

        $this->assertDatabaseHas('canvas_tags', [
            'id' => $response->original['id'],
            'slug' => $response->original['slug'],
            'user_id' => $response->original['user_id'],
        ]);

        $secondaryAdmin = User::factory()->admin()->create();

        $response = $this->actingAs($secondaryAdmin, 'canvas')
            ->putJson(route('canvas.tags.store', ['id' => Uuid::uuid4()->toString()]), [
                'name' => 'A new tag',
                'slug' => 'a-new-tag',
                'user_id' => $secondaryAdmin->id,
            ]);

        $this->assertDatabaseHas('canvas_tags', [
            'id' => $response->original['id'],
            'slug' => $response->original['slug'],
            'user_id' => $response->original['user_id'],
        ]);
    }

    public function testNameIsRequired(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->actingAs($tag->user, 'canvas')
            ->putJson(route('canvas.tags.store', ['id' => $tag->id]), [
                'slug' => 'a-new-tag',
                'user_id' => $tag->user->id,
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'name',
                ],
            ]);

        $this->assertSame(trans('canvas::app.name_required'), $response->getOriginalContent()['message']);
    }

    public function testUserIdIsRequired(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->actingAs($tag->user, 'canvas')
            ->putJson(route('canvas.tags.store', ['id' => $tag->id]), [
                'slug' => 'a-new-tag',
                'name' => 'A new tag',
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'user_id',
                ],
            ]);

        $this->assertSame(trans('canvas::app.user_id_required'), $response->getOriginalContent()['message']);
    }
}
