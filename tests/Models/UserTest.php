<?php

namespace Canvas\Tests\Models;

use Canvas\Canvas;
use Canvas\Models\Post;
use Canvas\Models\Tag;
use Canvas\Models\Topic;
use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

/**
 * Class UserTest.
 *
 * @covers \Canvas\Models\User
 * @covers \Canvas\Traits\HasRole
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testDarkModeIsCastToBoolean(): void
    {
        $this->assertSame('boolean', User::factory()->create()->getCasts()['dark_mode']);
    }

    public function testDigestIsCastToBoolean(): void
    {
        $this->assertSame('boolean', User::factory()->create()->getCasts()['digest']);
    }

    public function testRoleIsCastToInteger(): void
    {
        $this->assertSame('int', User::factory()->create()->getCasts()['role']);
    }

    public function testSocialIsCastToArray(): void
    {
        $this->assertIsArray(User::factory()->create()->social);
    }

    public function testMetaIsCastToArray(): void
    {
        $this->assertIsArray(User::factory()->create()->meta);
    }

    public function testDefaultAvatarAppendsToTheModel(): void
    {
        $this->assertArrayHasKey('default_avatar', User::factory()->create()->toArray());
    }

    public function testDefaultLocaleAppendsToTheModel(): void
    {
        $this->assertArrayHasKey('default_locale', User::factory()->create()->toArray());
    }

    public function testPasswordIsHiddenForArrays(): void
    {
        $this->assertArrayNotHasKey('password', User::factory()->create()->toArray());
    }

    public function testRememberTokenIsHiddenForArrays(): void
    {
        $this->assertArrayNotHasKey('remember_token', User::factory()->create([
            'remember_token' => Str::random(60),
        ])->toArray());
    }

    public function testPostsRelationship(): void
    {
        $user = User::factory()->has(Post::factory())->create();

        $this->assertInstanceOf(HasMany::class, $user->posts());
        $this->assertInstanceOf(Post::class, $user->posts->first());
    }

    public function testTagsRelationship(): void
    {
        $user = User::factory()->has(Tag::factory())->create();

        $this->assertInstanceOf(HasMany::class, $user->tags());
        $this->assertInstanceOf(Tag::class, $user->tags->first());
    }

    public function testTopicsRelationship(): void
    {
        $user = User::factory()->has(Topic::factory())->create();

        $this->assertInstanceOf(HasMany::class, $user->topics());
        $this->assertInstanceOf(Topic::class, $user->topics->first());
    }

    public function testContributorAttribute(): void
    {
        $this->assertTrue(User::factory()->contributor()->create()->isContributor);
    }

    public function testEditorAttribute(): void
    {
        $this->assertTrue(User::factory()->editor()->create()->isEditor);
    }

    public function testAdminAttribute(): void
    {
        $this->assertTrue(User::factory()->admin()->create()->isAdmin);
    }

    public function testDefaultAvatarAttribute(): void
    {
        $user = User::factory()->withoutAvatar()->create();

        $this->assertSame($user->defaultAvatar, Canvas::gravatar($user->email));
    }

    public function testDefaultLocaleAttribute(): void
    {
        $this->assertSame(User::factory()->withoutLocale()->create()->defaultLocale, config('app.locale'));
    }

    public function testAvailableRoles(): void
    {
        $this->assertCount(3, User::roles());

        $this->assertSame([
            User::$contributor_id => 'Contributor',
            User::$editor_id => 'Editor',
            User::$admin_id => 'Admin',
        ], User::roles());
    }

    public function testDeletePostsOnDelete(): void
    {
        $user = User::factory()->has(Post::factory())->create();

        $post = $user->posts()->first();

        $user->delete();

        $this->assertSoftDeleted('canvas_posts', ['id' => $post->id]);
    }

    public function testDeleteTagsOnDelete(): void
    {
        $user = User::factory()->has(Tag::factory())->create();

        $tag = $user->tags()->first();

        $user->delete();

        $this->assertSoftDeleted('canvas_tags', ['id' => $tag->id]);
    }

    public function testDeleteTopicsOnDelete(): void
    {
        $user = User::factory()->has(Topic::factory())->create();

        $topic = $user->topics()->first();

        $user->delete();

        $this->assertSoftDeleted('canvas_topics', ['id' => $topic->id]);
    }
}
