<?php

namespace Canvas\Tests\Http\Controllers\Auth;

use Canvas\Mail\ResetPassword;
use Canvas\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * Class PasswordResetLinkControllerTest.
 *
 * @covers \Canvas\Http\Controllers\Auth\PasswordResetLinkController
 * @covers \Canvas\Http\Requests\PasswordResetLinkRequest
 */
class PasswordResetLinkControllerTest extends TestCase
{
    public function testTheForgotPasswordPage(): void
    {
        $this->withoutMix();

        $this->get(route('canvas.forgot-password.view'))
             ->assertSuccessful()
             ->assertViewIs('canvas::auth.passwords.email')
             ->assertSeeText(trans('canvas::app.send_password_reset_link'));
    }

    public function testForgotPasswordLinkRequestWillValidateAnInvalidEmail(): void
    {
        $response = $this->post(route('canvas.forgot-password'), [
            'email' => 'not-an-email',
        ]);

        $this->assertInstanceOf(ValidationException::class, $response->exception);
    }

    public function testThePasswordResetLinkCanBeSent(): void
    {
        Mail::fake();

        $this->post(route('canvas.forgot-password'), [
            'email' => $this->admin->email,
        ])->assertRedirect(route('canvas.forgot-password.view'));

        Mail::assertSent(ResetPassword::class, function ($mail) {
            $this->assertIsString($mail->token);

            return $mail->hasTo($this->admin->email);
        });
    }
}
