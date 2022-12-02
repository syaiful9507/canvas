<?php

namespace Canvas\Tests\Http\Controllers\Auth;

use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Validation\ValidationException;

/**
 * Class AuthenticatedSessionControllerTest.
 *
 * @covers \Canvas\Http\Controllers\Auth\AuthenticatedSessionController
 * @covers \Canvas\Http\Requests\AuthenticatedSessionRequest
 */
class AuthenticatedSessionControllerTest extends TestCase
{
    public function testTheLoginPage(): void
    {
        $this->withoutMix();

        $this->get(route('canvas.login.view'))
             ->assertSuccessful()
             ->assertViewIs('canvas::auth.login')
             ->assertSeeText(trans('canvas::app.sign_in_to_your_account'));
    }

    public function testLoginRequestWillValidateAnInvalidEmail(): void
    {
        $response = $this->post(route('canvas.login'), [
            'email' => 'not-an-email',
            'password' => 'password',
        ])->assertRedirect(route('canvas.login'));

        $this->assertInstanceOf(ValidationException::class, $response->exception);
    }

    public function testLoginRequestWillValidateAnUnknownPassword(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('canvas.login'), [
            'email' => $user->email,
            'password' => 'what-is-my-password',
        ])->assertSessionHasErrors();

        $this->assertInstanceOf(ValidationException::class, $response->exception);
    }

    public function testSuccessfulLogin(): void
    {
        $user = User::factory()->create();

        $this->post(route('canvas.login.view'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(config('canvas.path'));
    }

    public function testAuthenticatedUserWillRedirectToCanvas(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'canvas')
             ->get(route('canvas.login.view'))
             ->assertRedirect(config('canvas.path'));
    }

    public function testSuccessfulLogout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'canvas')
             ->post(route('canvas.logout'))
             ->assertRedirect(route('canvas.login.view'));
    }
}
