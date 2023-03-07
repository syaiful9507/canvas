<?php

namespace Canvas\Tests\Console;

use Canvas\Mail\WeeklyDigest;
use Canvas\Models\Post;
use Canvas\Models\User;
use Canvas\Models\View;
use Canvas\Models\Visit;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

/**
 * Class DigestCommandTest.
 *
 * @covers \Canvas\Console\DigestCommand
 */
class DigestCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testDigestCommandWillSendAnEmailToUsersWithMailEnabled(): void
    {
        Mail::fake();

        $user = User::factory()
            ->has(Post::factory(2)
                ->published()
                ->has(View::factory(2))
                ->has(Visit::factory(1)))
            ->enabledDigest()
            ->create();

        $this->artisan('canvas:digest');

        Mail::assertSent(WeeklyDigest::class, function ($mail) use ($user) {
            $this->assertArrayHasKey('posts', $mail->data);
            $this->assertIsArray($mail->data['posts']);

            $this->assertArrayHasKey('views_count', $mail->data['posts'][0]);
            $this->assertArrayHasKey('visits_count', $mail->data['posts'][0]);

            $this->assertArrayHasKey('totals', $mail->data);
            $this->assertSame(4, $mail->data['totals']['views']);
            $this->assertSame(2, $mail->data['totals']['visits']);

            $this->assertArrayHasKey('startDate', $mail->data);
            $this->assertArrayHasKey('endDate', $mail->data);
            $this->assertArrayHasKey('locale', $mail->data);

            return $mail->hasTo($user->email);
        });
    }

    public function testDigestCommandWillNotSendAnEmailToUsersWithMailDisabled(): void
    {
        Mail::fake();

        $user = User::factory()->disabledDigest()->create();

        Post::factory(2)
            ->for($user)
            ->has(View::factory(2), 'views')
            ->has(Visit::factory(1), 'visits')
            ->create();

        $this->artisan('canvas:digest');

        Mail::assertNothingSent();
    }
}
