<?php

namespace Canvas\Tests\Models;

use Canvas\Models\Post;
use Canvas\Models\Tag;
use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TagTest.
 *
 * @covers \Canvas\Models\Tag
 */
class TagTest extends TestCase
{
    use RefreshDatabase;

    public function testMetaIsCastToArray(): void
    {
        $this->assertIsArray(Tag::factory()->create()->meta);
    }

    public function testPostsRelationship(): void
    {
        $tag = Tag::factory()->hasPosts(1)->create();

        $this->assertInstanceOf(BelongsToMany::class, $tag->posts());
        $this->assertInstanceOf(Post::class, $tag->posts()->first());
    }

    public function testUserRelationship(): void
    {
        $tag = Tag::factory()->hasUser()->create();

        $this->assertInstanceOf(BelongsTo::class, $tag->user());
        $this->assertInstanceOf(User::class, $tag->user);
    }

    public function testDetachPostsOnDelete(): void
    {
        $tag = Tag::factory()->hasPosts(1)->create();

        $post = $tag->posts()->first();

        $this->assertDatabaseHas('canvas_posts_tags', [
            'post_id' => $post->id,
            'tag_id' => $tag->id,
        ]);

        $tag->delete();

        $this->assertEquals(0, $tag->posts->count());
        $this->assertDatabaseMissing('canvas_posts_tags', [
            'post_id' => $post->id,
            'tag_id' => $tag->id,
        ]);
    }
}
