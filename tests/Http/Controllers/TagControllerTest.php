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
 * @covers \Canvas\Http\Requests\StoreTagRequest
 */
class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testListAllTags(): void
    {
        $tag = Tag::factory(2)->create();

        $response = $this->actingAs($tag->first()->user, 'canvas')
                         ->getJson('canvas/api/tags')
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
        $popularTag = Tag::factory()->has(Post::factory(2))->create();

        $unpopularTag = Tag::factory()->has(Post::factory())->create();

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
                         ->getJson('canvas/api/tags/create')
                         ->assertSuccessful();

        $this->assertInstanceOf(Tag::class, $response->getOriginalContent());
    }

    public function testExistingTagData(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->actingAs($tag->user, 'canvas')
                         ->getJson("canvas/api/tags/{$tag->id}")
                         ->assertSuccessful();

        $this->assertTrue($tag->is($response->getOriginalContent()));
    }

    public function testListPostsForTag(): void
    {
        $tag = Tag::factory()->has(Post::factory())->create();

        $response = $this->actingAs($tag->user, 'canvas')
                         ->getJson("canvas/api/tags/{$tag->id}/posts")
                         ->assertSuccessful();

        $this->assertInstanceOf(Post::class, $response->getOriginalContent()->first());

        $this->assertInstanceOf(LengthAwarePaginator::class, $response->getOriginalContent());

        $this->assertCount(1, $response->getOriginalContent());
    }

    public function testTagNotFound(): void
    {
        $this->actingAs(User::factory()->admin()->create(), 'canvas')
             ->getJson('canvas/api/tags/not-a-tag')
             ->assertNotFound();
    }

    public function testStoreNewTag(): void
    {
        $data = [
            'id' => Uuid::uuid4()->toString(),
            'name' => 'A new tag',
            'slug' => 'a-new-tag',
        ];

        $response = $this->actingAs(User::factory()->admin()->create(), 'canvas')
                         ->postJson("canvas/api/tags/{$data['id']}", $data)
                         ->assertSuccessful();

        $this->assertInstanceOf(Tag::class, $response->getOriginalContent()->first());

        $this->assertSame($data['id'], $response->getOriginalContent()->id);
    }

    public function testDeletedTagsCanBeRefreshed(): void
    {
        $deletedTag = Tag::factory()->create(['deleted_at' => now()]);

        $data = [
            'id' => Uuid::uuid4()->toString(),
            'name' => $deletedTag->name,
            'slug' => $deletedTag->slug,
        ];

        $response = $this->actingAs($deletedTag->user, 'canvas')
                         ->postJson("canvas/api/tags/{$data['id']}", $data)
                         ->assertSuccessful();

        $this->assertInstanceOf(Tag::class, $response->getOriginalContent()->first());

        $this->assertSame($deletedTag['id'], $response->getOriginalContent()->id);
    }

    public function testUpdateExistingTag(): void
    {
        $tag = Tag::factory()->create();

        $data = [
            'name' => 'An updated tag',
            'slug' => 'an-updated-tag',
        ];

        $response = $this->actingAs($tag->user, 'canvas')
                         ->postJson("canvas/api/tags/{$tag->id}", $data)
                         ->assertSuccessful();

        $this->assertInstanceOf(Tag::class, $response->getOriginalContent()->first());

        $this->assertSame($data['slug'], $response->getOriginalContent()->slug);
    }

    public function testInvalidSlugsAreValidated(): void
    {
        $tag = Tag::factory()->create();

        $this->actingAs($tag->user, 'canvas')
             ->postJson("canvas/api/tags/{$tag->id}", [
                 'name' => 'A new tag',
                 'slug' => 'a new.slug',
             ])
             ->assertStatus(422)
             ->assertJsonStructure([
                 'errors' => [
                     'slug',
                 ],
             ]);
    }

    public function testDeleteExistingTag(): void
    {
        $tag = Tag::factory()->create();

        $this->actingAs($tag->user, 'canvas')
             ->deleteJson('canvas/api/tags/not-a-tag')
             ->assertNotFound();

        $this->actingAs($tag->user, 'canvas')
             ->deleteJson("canvas/api/tags/{$tag->id}")
             ->assertSuccessful()
             ->assertNoContent();

        $this->assertSoftDeleted('canvas_tags', [
            'id' => $tag->id,
            'slug' => $tag->slug,
        ]);
    }
}
