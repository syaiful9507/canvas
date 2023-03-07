<?php

namespace Canvas\Tests\Http\Controllers;

use Canvas\Models\Post;
use Canvas\Models\Tag;
use Canvas\Models\Topic;
use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class SearchControllerTest.
 *
 * @covers \Canvas\Http\Controllers\SearchController
 * @covers \Canvas\Http\Middleware\VerifyAdmin
 */
class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAContributorCanOnlySearchTheirOwnPosts(): void
    {
        Post::factory(2)->create();

        $response = $this->actingAs(User::factory()->hasPosts(2)->contributor()->create(), 'canvas')
                         ->getJson(route('canvas.search.posts'))
                         ->assertSuccessful()
                         ->assertJsonCount(2);

        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('title', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('category', $response[0]);
        $this->assertSame('Posts', $response[0]['category']);
        $this->assertArrayHasKey('route', $response[0]);
        $this->assertSame('show-post', $response[0]['route']);
    }

    public function testAnEditorCanSearchAllPosts(): void
    {
        Post::factory(2)->create();

        $response = $this->actingAs(User::factory()->editor()->create(), 'canvas')
            ->getJson(route('canvas.search.posts'))
                         ->assertSuccessful()
                         ->assertJsonCount(2);

        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('title', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('category', $response[0]);
        $this->assertSame('Posts', $response[0]['category']);
        $this->assertArrayHasKey('route', $response[0]);
        $this->assertSame('show-post', $response[0]['route']);
    }

    public function testAnAdminCanSearchAllPosts(): void
    {
        Post::factory(2)->create();

        $response = $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.search.posts'))
                         ->assertSuccessful()
                         ->assertJsonCount(2);

        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('title', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('category', $response[0]);
        $this->assertSame('Posts', $response[0]['category']);
        $this->assertArrayHasKey('route', $response[0]);
        $this->assertSame('show-post', $response[0]['route']);
    }

    public function testAnAdminCanSearchAllTags(): void
    {
        Tag::factory(2)->create();

        $response = $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.search.tags'))
                         ->assertSuccessful()
                         ->assertJsonCount(2);

        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('category', $response[0]);
        $this->assertSame('Tags', $response[0]['category']);
        $this->assertArrayHasKey('route', $response[0]);
        $this->assertSame('show-tag', $response[0]['route']);
    }

    public function testAnAdminCanSearchAllTopics(): void
    {
        Topic::factory(2)->create();

        $response = $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.search.topics'))
                         ->assertSuccessful()
                         ->assertJsonCount(2);

        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('category', $response[0]);
        $this->assertSame('Topics', $response[0]['category']);
        $this->assertArrayHasKey('route', $response[0]);
        $this->assertSame('show-topic', $response[0]['route']);
    }

    public function testAnAdminCanSearchAllUsers(): void
    {
        User::factory(2)->create();

        $response = $this->actingAs(User::factory()->admin()->create(), 'canvas')
            ->getJson(route('canvas.search.users'))
                         ->assertSuccessful()
                         ->assertJsonCount(3);

        $this->assertArrayHasKey('id', $response[0]);
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('category', $response[0]);
        $this->assertSame('Users', $response[0]['category']);
        $this->assertArrayHasKey('route', $response[0]);
        $this->assertSame('show-user', $response[0]['route']);
    }
}
