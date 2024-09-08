<?php

namespace Tests\Unit\Service;

use App\Exceptions\AbbeyException;
use App\Models\User;
use App\Services\AuthenticationService;
use App\Support\PendingUserRegistration;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationServiceTest extends TestCase
{
    public function testRegisterCreatesPendingUserRegistration()
    {
        $email = 'test@example.com';

        $pendingUserRegistration = AuthenticationService::register($email);

        $this->assertInstanceOf(PendingUserRegistration::class, $pendingUserRegistration);
    }

    public function testLoginReturnsUserOnValidCredentials()
    {
        $password = 'password123';
        $hashedPassword = Hash::make($password);
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => $hashedPassword,
        ]);

        $loggedInUser = AuthenticationService::login($user->email, $password);

        $this->assertInstanceOf(User::class, $loggedInUser);
        $this->assertEquals($user->id, $loggedInUser->id);
    }

    public function testLoginThrowsExceptionForInvalidEmail()
    {
        $email = 'nonexistent@example.com';
        $password = 'password123';

        $this->expectException(AbbeyException::class);
        $this->expectExceptionMessage('Invalid email or password');

        AuthenticationService::login($email, $password);
    }

    public function testLoginThrowsExceptionForDisabledUser()
    {
        $user = User::factory()->create([
            'email' => 'disableduser@example.com',
            'password' => Hash::make('password123'),
            'disabled_at' => now(),
        ]);

        $this->expectException(AbbeyException::class);
        $this->expectExceptionMessage('User account is disabled; contact support for help.');

        AuthenticationService::login($user->email, 'password123');
    }

}
