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
 */
class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAContributorCanOnlySearchTheirOwnPosts(): void
    {
        $contributor = User::factory()->contributor()->create();

        Post::factory(2)->for($contributor)->create();

        Post::factory()->for(User::factory()->admin())->create();

        $response = $this->actingAs($contributor, 'canvas')
                         ->getJson('canvas/api/search/posts')
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
        $editor = User::factory()->editor()->create();

        Post::factory(2)->for($editor)->create();

        Post::factory()->for(User::factory()->contributor())->create();

        $response = $this->actingAs($editor, 'canvas')
                         ->getJson('canvas/api/search/posts')
                         ->assertSuccessful()
                         ->assertJsonCount(3);

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
        $admin = User::factory()->admin()->create();

        Post::factory(2)->for(User::factory()->editor())->create();

        Post::factory()->for(User::factory()->contributor())->create();

        $response = $this->actingAs($admin, 'canvas')
                         ->getJson('canvas/api/search/posts')
                         ->assertSuccessful()
                         ->assertJsonCount(3);

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
        $user = User::factory()->admin()->has(Tag::factory(2))->create();

        $response = $this->actingAs($user, 'canvas')
                         ->getJson('canvas/api/search/tags')
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
        $user = User::factory()->admin()->has(Topic::factory(2))->create();

        $response = $this->actingAs($user, 'canvas')
                         ->getJson('canvas/api/search/topics')
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
        $admin = User::factory()->admin()->create();

        User::factory()->editor()->create();

        User::factory()->contributor()->create();

        $response = $this->actingAs($admin, 'canvas')
                         ->getJson('canvas/api/search/users')
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
