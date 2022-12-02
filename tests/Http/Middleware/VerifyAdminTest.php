<?php

namespace Canvas\Tests\Http\Middleware;

use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class VerifyAdminTest.
 *
 * @covers \Canvas\Http\Middleware\VerifyAdmin
 */
class VerifyAdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array
     */
    public function protectedRoutesProvider(): array
    {
        return [
            // Tag routes...
            ['GET', 'canvas/api/tags'],
            ['GET', 'canvas/api/tags/create'],

            // Topic routes...
            ['GET', 'canvas/api/topics'],
            ['GET', 'canvas/api/topics/create'],

            // User routes...
            ['GET', 'canvas/api/users'],
            ['GET', 'canvas/api/users/create'],

            // Search routes...
            ['GET', 'canvas/api/search/tags'],
            ['GET', 'canvas/api/search/topics'],
            ['GET', 'canvas/api/search/users'],
        ];
    }

    /**
     * @dataProvider protectedRoutesProvider
     *
     * @param $method
     * @param $endpoint
     */
    public function testContributorAccessIsRestricted($method, $endpoint)
    {
        $this->actingAs(User::factory()->contributor()->create(), 'canvas')
             ->call($method, $endpoint)
             ->assertForbidden();
    }

    /**
     * @dataProvider protectedRoutesProvider
     *
     * @param $method
     * @param $endpoint
     */
    public function testEditorAccessIsRestricted($method, $endpoint)
    {
        $this->actingAs(User::factory()->editor()->create(), 'canvas')
             ->call($method, $endpoint)
             ->assertForbidden();
    }

    /**
     * @dataProvider protectedRoutesProvider
     *
     * @param $method
     * @param $endpoint
     */
    public function testAdminAccessIsGranted($method, $endpoint)
    {
        $this->actingAs(User::factory()->admin()->create(), 'canvas')
             ->call($method, $endpoint)
             ->assertSuccessful();
    }
}
