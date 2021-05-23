<?php

declare(strict_types=1);

namespace Canvas\Console;

use Canvas\Canvas;
use Canvas\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'canvas:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user for Canvas';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $email = $this->ask('What email should be attached to the user?');
        $password = 'password';

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Please enter a valid email.');

            return;
        }

        $role = $this->choice(
            'What role should the user have?',
            Canvas::availableRoles(),
            null,
            $maxAttempts = null,
            $allowMultipleSelections = false
        );

        $user = User::query()->make([
            'id' => Uuid::uuid4()->toString(),
            'name' => 'New User',
            'email' => $email,
            'password' => Hash::make($password),
            'avatar' => Canvas::gravatar($email),
            'role' => $role,
        ]);

        $user->save();

        $this->info('New user created.');
        $this->table(['Email', 'Password'], [[$email, $password]]);
        $this->info('First things first, head to <info>'.route('canvas.users.show', $user->id).'</info> and update your credentials.');
    }
}
