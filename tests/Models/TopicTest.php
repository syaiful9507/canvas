<?php

namespace Canvas\Tests\Models;

use Canvas\Models\Post;
use Canvas\Models\Topic;
use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TopicTest.
 *
 * @covers \Canvas\Models\Topic
 */
class TopicTest extends TestCase
{
    use RefreshDatabase;

    public function testMetaIsCastToArray(): void
    {
        $this->assertIsArray(Topic::factory()->create()->meta);
    }

    public function testPostsRelationship(): void
    {
        $topic = Topic::factory()->hasPosts(1)->create();

        $this->assertInstanceOf(HasMany::class, $topic->posts());
        $this->assertInstanceOf(Post::class, $topic->posts->first());
    }

    public function testUserRelationship(): void
    {
        $topic = Topic::factory()->hasUser()->create();

        $this->assertInstanceOf(BelongsTo::class, $topic->user());
        $this->assertInstanceOf(User::class, $topic->user);
    }

    public function testDissociatePostsOnDelete(): void
    {
        $topic = Topic::factory()->hasPosts(1)->create();

        $post = $topic->posts()->first();

        $topic->delete();

        $this->assertNull($post->topic);
    }
}
