<?php

namespace App\Providers;

use App\Http\Requests\LoginRequest;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\URL;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::verifyEmailView(function (Request $request) {
            $user = $request->user();

            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                [
                    'id' => $user->id,
                    'hash' => sha1($user->getEmailForVerification()),
                ]
            );
            return view('auth.verify', compact('verificationUrl'));
        });

        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::authenticateUsing(function (Request $request) {
            Validator::make($request->all(),
            app(LoginRequest::class)->rules(),
            app(LoginRequest::class)->messages())
            ->validate();

            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();

                if (!$user->hasVerifiedEmail()) {
                    return $user;
                }
                return $user;
            }

            throw ValidationException::withMessages([
                'email' => ['ログイン情報が登録されていません'],
            ]);
        });


        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });
    }
}
