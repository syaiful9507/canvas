<?php

namespace Canvas\Tests\Http\Requests;

use Canvas\Models\Topic;
use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;

/**
 * Class StoreTopicRequestTest.
 *
 * @covers \Canvas\Http\Requests\StoreTopicRequest
 */
class StoreTopicRequestTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminRoleIsRequired(): void
    {
        $this->actingAs($user = User::factory()->contributor()->create(), 'canvas')
            ->putJson(route('canvas.topics.store', ['id' => Uuid::uuid4()->toString()]), [
                'name' => 'A new topic',
                'slug' => 'a-new-topic',
                'user_id' => $user->id,
            ])
            ->assertForbidden();

        $this->actingAs($user = User::factory()->editor()->create(), 'canvas')
            ->putJson(route('canvas.topics.store', ['id' => Uuid::uuid4()->toString()]), [
                'name' => 'A new topic',
                'slug' => 'a-new-topic',
                'user_id' => $user->id,
            ])
            ->assertForbidden();

        $this->actingAs($user = User::factory()->admin()->create(), 'canvas')
            ->putJson(route('canvas.topics.store', ['id' => Uuid::uuid4()->toString()]), [
                'name' => 'A new topic',
                'slug' => 'a-new-topic',
                'user_id' => $user->id,
            ])
            ->assertSuccessful();
    }

    public function testSlugIsRequired(): void
    {
        $topic = Topic::factory()->create();

        $response = $this->actingAs($topic->user, 'canvas')
            ->putJson(route('canvas.topics.store', ['id' => $topic->id]), [
                'name' => $topic->name,
                'user_id' => $topic->user->id,
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
        $topic = Topic::factory()->create();

        $response = $this->actingAs($topic->user, 'canvas')
            ->putJson(route('canvas.topics.store', ['id' => $topic->id]), [
                'slug' => 'a new.slug',
                'name' => $topic->name,
                'user_id' => $topic->user->id,
            ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'slug',
                ],
            ]);

        $this->assertSame(trans('canvas::app.slug_alpha_dash'), $response->getOriginalContent()['message']);
    }

    public function testTopicsCanShareTheSameSlugWithUniqueUsers(): void
    {
        $primaryAdmin = User::factory()->admin()->create();

        $response = $this->actingAs($primaryAdmin, 'canvas')
            ->putJson(route('canvas.topics.store', ['id' => Uuid::uuid4()->toString()]), [
                'name' => 'A new topic',
                'slug' => 'a-new-topic',
                'user_id' => $primaryAdmin->id,
            ]);

        $this->assertDatabaseHas('canvas_topics', [
            'id' => $response->original['id'],
            'slug' => $response->original['slug'],
            'user_id' => $response->original['user_id'],
        ]);

        $secondaryAdmin = User::factory()->admin()->create();

        $response = $this->actingAs($secondaryAdmin, 'canvas')
            ->putJson(route('canvas.topics.store', ['id' => Uuid::uuid4()->toString()]), [
                'name' => 'A new topic',
                'slug' => 'a-new-topic',
                'user_id' => $secondaryAdmin->id,
            ]);

        $this->assertDatabaseHas('canvas_topics', [
            'id' => $response->original['id'],
            'slug' => $response->original['slug'],
            'user_id' => $response->original['user_id'],
        ]);
    }

    public function testNameIsRequired(): void
    {
        $topic = Topic::factory()->create();

        $response = $this->actingAs($topic->user, 'canvas')
            ->putJson(route('canvas.topics.store', ['id' => $topic->id]), [
                'slug' => 'a-new-topic',
                'user_id' => $topic->user->id,
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
        $topic = Topic::factory()->create();

        $response = $this->actingAs($topic->user, 'canvas')
            ->putJson(route('canvas.topics.store', ['id' => $topic->id]), [
                'slug' => 'a-new-topic',
                'name' => 'A new topic',
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
