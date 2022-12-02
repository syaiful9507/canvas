<?php

namespace Canvas\Tests\Models;

use Canvas\Models\Post;
use Canvas\Models\Visit;
use Canvas\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class VisitTest.
 *
 * @covers \Canvas\Models\Visit
 */
class VisitTest extends TestCase
{
    use RefreshDatabase;

    public function testPostRelationship(): void
    {
        $visit = Visit::factory()->has(Post::factory())->create();

        $this->assertInstanceOf(BelongsTo::class, $visit->post());
        $this->assertInstanceOf(Post::class, $visit->post()->first());
    }
}
