<?php

namespace Canvas\Tests\Console;

use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class AdminCommandTest.
 *
 * @covers \Canvas\Console\UserCommand
 */
class UserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testCanvasUserCommandWillValidateAnEmptyEmail(): void
    {
        $this->artisan('canvas:user')
             ->expectsQuestion('What email should be attached to the user?', '')
             ->assertExitCode(0)
             ->expectsOutput('Please enter a valid email.');
    }

    public function testCanvasUserCommandWillValidateAnInvalidEmail(): void
    {
        $this->artisan('canvas:user')
             ->expectsQuestion('What email should be attached to the user?', 'bad.email')
             ->assertExitCode(0)
             ->expectsOutput('Please enter a valid email.');
    }

    public function testCanvasUserCommandWillValidateAnExistingEmail(): void
    {
        $this->artisan('canvas:user')
             ->expectsQuestion('What email should be attached to the user?', $this->contributor->email)
             ->assertExitCode(0)
             ->expectsOutput('That email already exists in the system.');
    }

    public function testCanvasUserCommandCanCreateANewContributor(): void
    {
        $this->artisan('canvas:user')
             ->expectsQuestion('What email should be attached to the user?', 'contributor@example.com')
             ->expectsChoice('What role should the user have?', User::$contributor_name, User::roles())
             ->assertExitCode(0)
             ->expectsOutput('New user created.');

        $this->assertDatabaseHas('canvas_users', [
            'email' => 'contributor@example.com',
            'role' => User::$contributor_id,
        ]);
    }

    public function testCanvasUserCommandCanCreateANewEditor(): void
    {
        $this->artisan('canvas:user')
             ->expectsQuestion('What email should be attached to the user?', 'editor@example.com')
            ->expectsChoice('What role should the user have?', User::$editor_name, User::roles())
             ->assertExitCode(0)
             ->expectsOutput('New user created.');

        $this->assertDatabaseHas('canvas_users', [
            'email' => 'editor@example.com',
            'role' => User::$editor_id,
        ]);
    }

    public function testCanvasUserCommandCanCreateANewAdmin(): void
    {
        $this->artisan('canvas:user')
             ->expectsQuestion('What email should be attached to the user?', 'admin@example.com')
            ->expectsChoice('What role should the user have?', User::$admin_name, User::roles())
             ->assertExitCode(0)
             ->expectsOutput('New user created.');

        $this->assertDatabaseHas('canvas_users', [
            'email' => 'admin@example.com',
            'role' => User::$admin_id,
        ]);
    }
}
