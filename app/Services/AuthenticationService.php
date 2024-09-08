<?php

namespace App\Services;

use App\Exceptions\AbbeyException;
use App\Models\User;
use App\Support\PendingUserRegistration;
use Carbon\Carbon;

class AuthenticationService
{
    public static function register(string $email): PendingUserRegistration
    {
        return new PendingUserRegistration($email);
    }

    public static function login(string $email, string $password): User
    {
        /** @var User $user */
        $user = User::where('email', $email)->first();

        throw_if($user === null,
        new AbbeyException('Invalid email or password', 401)
        );

        throw_if( !password_verify($password, $user->password),
            new AbbeyException('Invalid email or password', 401));


        throw_if(
            $user->disabled_at !== null,
            new AbbeyException('User account is disabled; contact support for help.', 403)
        );

        return $user;
    }
}
