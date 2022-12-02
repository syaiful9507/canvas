<?php

namespace Canvas\Tests\Models;

use Canvas\Models\Post;
use Canvas\Models\View;
use Canvas\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ViewTest.
 *
 * @covers \Canvas\Models\View
 */
class ViewTest extends TestCase
{
    use RefreshDatabase;

    public function testPostRelationship(): void
    {
        $view = View::factory()->has(Post::factory())->create();

        $this->assertInstanceOf(BelongsTo::class, $view->post());
        $this->assertInstanceOf(Post::class, $view->post()->first());
    }
}
