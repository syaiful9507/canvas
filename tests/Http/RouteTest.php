<?php

namespace Canvas\Tests\Http;

use Canvas\Canvas;
use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Support\Facades\Config;

class RouteTest extends TestCase
{
    public function testNamedRoute(): void
    {
        $this->assertEquals(
            url(config('canvas.path')),
            route('canvas')
        );
    }

    public function testRouteWithDefaultBasePath(): void
    {
        $this->actingAs(User::factory()->create())
             ->get(route('canvas'))
             ->assertRedirect(route('canvas.login'))
             ->assertLocation('http://laravel.test/canvas/login');

        $this->assertSame(Canvas::basePath(), '/canvas');
    }

    public function testRouteWithSubdomainAndDefaultBasePath(): void
    {
        Config::set('canvas.domain', 'http://canvas.laravel.test');

        $this->actingAs(User::factory()->admin()->create())
             ->get(config('canvas.domain').'/canvas')
             ->assertRedirect(route('canvas.login'))
             ->assertLocation('http://canvas.laravel.test/canvas/login');

        $this->assertSame(Canvas::basePath(), '/canvas');
    }

    public function testRouteWithSubdomainAndNullBasePath(): void
    {
        Config::set('canvas.path', null);

        Config::set('canvas.domain', 'http://canvas.laravel.test');

        $this->actingAs(User::factory()->admin()->create())
             ->get(config('canvas.domain').'/canvas')
             ->assertRedirect(route('canvas.login'));

        $this->assertSame(Canvas::basePath(), '/');
    }
}
