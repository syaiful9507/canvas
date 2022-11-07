<?php

namespace Canvas\Tests\Http\Controllers\Auth;

use Canvas\Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Class NewPasswordControllerTest.
 *
 * @covers \Canvas\Http\Controllers\Auth\NewPasswordController
 * @covers \Canvas\Http\Requests\NewPasswordRequest
 */
class NewPasswordControllerTest extends TestCase
{
    public function testTheResetPasswordPage(): void
    {
        $this->withoutMix();

        $this->get(route('canvas.reset-password.view', [
            'token' => encrypt($this->admin->id.'|'.Str::random()),
        ]))
             ->assertSuccessful()
             ->assertViewIs('canvas::auth.passwords.reset')
             ->assertSeeText(trans('canvas::app.update_password'));
    }

    public function testPasswordCanBeReset(): void
    {
        $this->withoutMix();

        $token = encrypt($this->admin->id.'|'.Str::random());

        cache(["password.reset.{$this->admin->id}" => $token],
            now()->addMinutes(60)
        );

        $this->post(route('canvas.reset-password', [
            'token' => $token,
            'email' => $this->admin->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]))->assertRedirect(route('canvas'));

        $this->assertEmpty(cache("password.reset.{$this->admin->id}"));
    }

    public function testNewPasswordRequestWillValidateAnInvalidEmail(): void
    {
        $response = $this->post(route('canvas.reset-password'), [
            'token' =>  encrypt($this->admin->id.'|'.Str::random()),
            'email' => 'not-an-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertInstanceOf(ValidationException::class, $response->exception);
    }

    public function testNewPasswordRequestWillValidateUnconfirmedPasswords(): void
    {
        $response = $this->post(route('canvas.reset-password'), [
            'token' =>  encrypt($this->admin->id.'|'.Str::random()),
            'email' => $this->admin->email,
            'password' => 'password',
            'password_confirmation' => 'secret',
        ]);

        $this->assertInstanceOf(ValidationException::class, $response->exception);
    }

    public function testNewPasswordRequestWillValidateExpiredTokens(): void
    {
        $oldToken = encrypt($this->admin->id.'|'.Str::random());

        cache(["password.reset.{$this->admin->id}" => $oldToken],
            now()->subMinute()
        );

        $this->post(route('canvas.reset-password'), [
            'token' => $oldToken,
            'email' => $this->admin->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHas('invalidResetToken');
    }
}
