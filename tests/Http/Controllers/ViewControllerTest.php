<?php

namespace Canvas\Tests\Http\Controllers;

use Canvas\Models\User;
use Canvas\Tests\TestCase;

/**
 * Class ViewControllerTest.
 *
 * @covers \Canvas\Http\Controllers\ViewController
 */
class ViewControllerTest extends TestCase
{
    /** @test */
    public function testScriptVariables(): void
    {
        $this->withoutMix();

        $this->actingAs(User::factory()->create(), 'canvas')
             ->get(config('canvas.path'))
             ->assertSuccessful()
             ->assertViewIs('canvas::layout')
             ->assertViewHas('scripts')
             ->assertSee('canvas');
    }
}
