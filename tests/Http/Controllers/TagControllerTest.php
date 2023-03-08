<?php

namespace Canvas\Tests\Http\Controllers;

use Canvas\Models\Post;
use Canvas\Models\Tag;
use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Uuid\Uuid;

/**
 * Class TagControllerTest.
 *
 * @covers \Canvas\Http\Controllers\TagController
 */
class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAllTagsAreFetchedByDefault(): void
    {
        Tag::factory(2)->create();

        $response = $this->actingAs(User::factory()->admin()->create(), 'canvas')
                         ->getJson(route('canvas.tags.index'))
                         ->assertSuccessful();

        $this->assertInstanceOf(Tag::class, $response->getOriginalContent()->first());

        $this->assertInstanceOf(LengthAwarePaginator::class, $response->getOriginalContent());

        $this->assertCount(2, $response->getOriginalContent());
    }

    public function testTagsCanBeSortedByCreationDateWithAGivenQueryParameter(): void
    {
        $tag = Tag::factory()->create(['created_at' => now()]);

        $oldTag = Tag::factory()->create(['created_at' => now()->subDay()]);

        $response = $this->actingAs($tag->user, 'canvas')
            ->getJson(route('canvas.tags.index', ['sort' => 'desc']))
            ->assertSuccessful();

        $this->assertSame($response->getOriginalContent()->first()->id, $tag->id);

        $response = $this->actingAs($tag->user, 'canvas')
            ->getJson(route('canvas.tags.index', ['sort' => 'asc']))
            ->assertSuccessful();

        $this->assertSame($response->getOriginalContent()->first()->id, $oldTag->id);

        $response = $this->actingAs($tag->user, 'canvas')
            ->getJson(route('canvas.tags.index'))
            ->assertSuccessful();

        $this->assertSame($response->getOriginalContent()->first()->id, $tag->id);
    }

    public function testTagsCanBeFilteredByUsageWithAGivenQueryParameter(): void
    {
        $popularTag = Tag::factory()->hasPosts(2)->create();

        $unpopularTag = Tag::factory()->hasPosts(1)->create();

        $response = $this->actingAs($popularTag->user, 'canvas')
            ->getJson(route('canvas.tags.index', ['usage' => 'popular']))
            ->assertSuccessful();

        $this->assertSame($response->getOriginalContent()->first()->id, $popularTag->id);

        $response = $this->actingAs($popularTag->user, 'canvas')
            ->getJson(route('canvas.tags.index', ['usage' => 'unpopular']))
            ->assertSuccessful();

        $this->assertSame($response->getOriginalContent()->first()->id, $unpopularTag->id);
    }

    public function testCreateDataForTag(): void
    {
        $response = $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.tags.create'))
            ->assertSuccessful();

        $this->assertInstanceOf(Tag::class, $response->getOriginalContent());
    }

    public function testExistingTagData(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->actingAs($tag->user, 'canvas')
                         ->getJson(route('canvas.tags.show', ['id' => $tag->id]))
                         ->assertSuccessful();

        $this->assertTrue($tag->is($response->getOriginalContent()));
    }

    public function testListPostsForTag(): void
    {
        $tag = Tag::factory()->hasPosts(1)->create();

        $response = $this->actingAs($tag->user, 'canvas')
                        ->getJson(route('canvas.tags.posts', ['id' => $tag->id]))
                         ->assertSuccessful();

        $this->assertInstanceOf(Post::class, $response->getOriginalContent()->first());

        $this->assertInstanceOf(LengthAwarePaginator::class, $response->getOriginalContent());

        $this->assertCount(1, $response->getOriginalContent());
    }

    public function testTagNotFound(): void
    {
        $this->actingAs(User::factory()->admin()->create(), 'canvas')
             ->getJson(route('canvas.tags.show', ['id' => Uuid::uuid4()->toString()]))
             ->assertNotFound();
    }

    public function testStoreNewTag(): void
    {
        $user = User::factory()->admin()->create();

        $data = [
            'name' => 'A new tag',
            'slug' => 'a-new-tag',
            'user_id' => $user->id,
        ];

        $response = $this->actingAs($user, 'canvas')
                         ->putJson(route('canvas.tags.store', ['id' => Uuid::uuid4()->toString()]), $data)
                         ->assertSuccessful();

        $this->assertInstanceOf(Tag::class, $response->getOriginalContent()->first());

        $this->assertSame($data['slug'], $response->getOriginalContent()->slug);
    }

    public function testUpdateExistingTag(): void
    {
        $tag = Tag::factory()->create();

        $data = [
            'name' => 'An updated tag',
            'slug' => 'an-updated-tag',
            'user_id' => $tag->user_id,
        ];

        $response = $this->actingAs($tag->user, 'canvas')
                            ->putJson(route('canvas.tags.store', ['id' => $tag->id]), $data)
                         ->assertSuccessful();

        $this->assertInstanceOf(Tag::class, $response->getOriginalContent()->first());

        $this->assertSame($data['slug'], $response->getOriginalContent()->slug);
    }

    public function testDeleteExistingTag(): void
    {
        $tag = Tag::factory()->create();

        $this->actingAs($tag->user, 'canvas')
            ->deleteJson(route('canvas.tags.destroy', ['id' => $tag->id]))
             ->assertSuccessful()
             ->assertNoContent();

        $this->assertSoftDeleted('canvas_tags', [
            'id' => $tag->id,
            'slug' => $tag->slug,
        ]);
    }

    public function testShowTagMethodValidatesUuidParameter(): void
    {
        $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.tags.show', ['id' => 'not-a-tag']))
            ->assertBadRequest();
    }

    public function testStoreTagMethodValidatesUuidParameter(): void
    {
        $this->actingAs($user = User::factory()->admin()->create(), 'canvas')
            ->putJson(route('canvas.tags.store', ['id' => 'not-a-tag']), [
                'slug' => 'a-new-tag',
                'name' => 'A new tag',
                'user_id' => $user->id,
            ])
            ->assertBadRequest();
    }

    public function testGetPostsForTagMethodValidatesUuidParameter(): void
    {
        $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.tags.posts', ['id' => 'not-a-tag']))
            ->assertBadRequest();
    }

    public function testDestroyTagMethodValidatesUuidParameter(): void
    {
        $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.tags.destroy', ['id' => 'not-a-tag']))
            ->assertBadRequest();
    }
}
