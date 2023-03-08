<?php

namespace Canvas\Tests\Http\Controllers;

use Canvas\Models\Post;
use Canvas\Models\Topic;
use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Uuid\Uuid;

/**
 * Class TopicControllerTest.
 *
 * @covers \Canvas\Http\Controllers\TopicController
 */
class TopicControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAllTopicsAreFetchedByDefault(): void
    {
        Topic::factory(2)->create();

        $response = $this->actingAs(User::factory()->admin()->create(), 'canvas')
                         ->getJson(route('canvas.topics.index'))
                         ->assertSuccessful();

        $this->assertInstanceOf(Topic::class, $response->getOriginalContent()->first());

        $this->assertInstanceOf(LengthAwarePaginator::class, $response->getOriginalContent());

        $this->assertCount(2, $response->getOriginalContent());
    }

    public function testTopicsCanBeSortedByCreationDateWithAGivenQueryParameter(): void
    {
        $topic = Topic::factory()->create(['created_at' => now()]);

        $oldTopic = Topic::factory()->create(['created_at' => now()->subDay()]);

        $response = $this->actingAs($topic->user, 'canvas')
            ->getJson(route('canvas.topics.index', ['sort' => 'desc']))
            ->assertSuccessful();

        $this->assertSame($response->getOriginalContent()->first()->id, $topic->id);

        $response = $this->actingAs($topic->user, 'canvas')
            ->getJson(route('canvas.topics.index', ['sort' => 'asc']))
            ->assertSuccessful();

        $this->assertSame($response->getOriginalContent()->first()->id, $oldTopic->id);

        $response = $this->actingAs($topic->user, 'canvas')
            ->getJson(route('canvas.topics.index'))
            ->assertSuccessful();

        $this->assertSame($response->getOriginalContent()->first()->id, $topic->id);
    }

    public function testTopicsCanBeFilteredByUsageWithAGivenQueryParameter(): void
    {
        $popularTopic = Topic::factory()->has(Post::factory(2))->create();

        $unpopularTopic = Topic::factory()->has(Post::factory())->create();

        $response = $this->actingAs($popularTopic->user, 'canvas')
            ->getJson(route('canvas.topics.index', ['usage' => 'popular']))
            ->assertSuccessful();

        $this->assertSame($response->getOriginalContent()->first()->id, $popularTopic->id);

        $response = $this->actingAs($popularTopic->user, 'canvas')
            ->getJson(route('canvas.topics.index', ['usage' => 'unpopular']))
            ->assertSuccessful();

        $this->assertSame($response->getOriginalContent()->first()->id, $unpopularTopic->id);
    }

    public function testCreateDataForTopic(): void
    {
        $response = $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.topics.create'))
            ->assertSuccessful();

        $this->assertInstanceOf(Topic::class, $response->getOriginalContent());
    }

    public function testExistingTopicData(): void
    {
        $topic = Topic::factory()->create();

        $response = $this->actingAs($topic->user, 'canvas')
            ->getJson(route('canvas.topics.show', ['id' => $topic->id]))
                         ->assertSuccessful();

        $this->assertTrue($topic->is($response->getOriginalContent()));
    }

    public function testListPostsForTopic(): void
    {
        $topic = Topic::factory()->hasPosts(1)->create();

        $response = $this->actingAs($topic->user, 'canvas')
            ->getJson(route('canvas.topics.posts', ['id' => $topic->id]))
                         ->assertSuccessful();

        $this->assertInstanceOf(Post::class, $response->getOriginalContent()->first());

        $this->assertInstanceOf(LengthAwarePaginator::class, $response->getOriginalContent());

        $this->assertCount(1, $response->getOriginalContent());
    }

    public function testTopicNotFound(): void
    {
        $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.topics.posts', ['id' => Uuid::uuid4()->toString()]))
             ->assertNotFound();
    }

    public function testStoreNewTopic(): void
    {
        $user = User::factory()->admin()->create();

        $data = [
            'name' => 'A new topic',
            'slug' => 'a-new-topic',
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user, 'canvas')
            ->putJson(route('canvas.topics.store', ['id' => Uuid::uuid4()->toString()]), $data)
                         ->assertSuccessful();

        $this->assertInstanceOf(Topic::class, $response->getOriginalContent()->first());

        $this->assertSame($data['slug'], $response->getOriginalContent()->slug);
    }

    public function testUpdateExistingTopic(): void
    {
        $topic = Topic::factory()->create();

        $data = [
            'name' => 'An updated topic',
            'slug' => 'an-updated-topic',
            'user_id' => $topic->user_id,
        ];

        $response = $this->actingAs($topic->user, 'canvas')
            ->putJson(route('canvas.topics.store', ['id' => $topic->id]), $data)
                         ->assertSuccessful();

        $this->assertInstanceOf(Topic::class, $response->getOriginalContent()->first());

        $this->assertSame($data['slug'], $response->getOriginalContent()->slug);
    }

    public function testDeleteExistingTopic(): void
    {
        $topic = Topic::factory()->create();

        $this->actingAs($topic->user, 'canvas')
            ->deleteJson(route('canvas.topics.destroy', ['id' => $topic->id]))
            ->assertSuccessful()
            ->assertNoContent();

        $this->assertSoftDeleted('canvas_topics', [
            'id' => $topic->id,
            'slug' => $topic->slug,
        ]);
    }

    public function testShowTopicMethodValidatesUuidParameter(): void
    {
        $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.topics.show', ['id' => 'not-a-topic']))
            ->assertStatus(400);
    }

    public function testStoreTopicMethodValidatesUuidParameter(): void
    {
        $this->actingAs($user = User::factory()->admin()->create(), 'canvas')
            ->putJson(route('canvas.topics.store', ['id' => 'not-a-topic']), [
                'slug' => 'a-new-topic',
                'name' => 'A new topic',
                'user_id' => $user->id,
            ])
            ->assertStatus(400);
    }

    public function testGetPostsForTopicMethodValidatesUuidParameter(): void
    {
        $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.topics.posts', ['id' => 'not-a-topic']))
            ->assertStatus(400);
    }

    public function testDestroyTopicMethodValidatesUuidParameter(): void
    {
        $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.topics.destroy', ['id' => 'not-a-topic']))
            ->assertStatus(400);
    }
}
