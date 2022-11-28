<?php

namespace Canvas\Tests\Console;

use Canvas\Tests\TestCase;

/**
 * Class PublishCommandTest.
 *
 * @covers \Canvas\Console\PublishCommand
 */
class PublishCommandTest extends TestCase
{
    public function testCanvasPublishCommand(): void
    {
        $this->artisan('canvas:publish')
             ->assertExitCode(0)
             ->expectsOutput('Publishing complete.');
    }
}
