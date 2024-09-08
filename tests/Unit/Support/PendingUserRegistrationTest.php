<?php

namespace Tests\Unit\Support;

use App\Exceptions\AbbeyException;
use App\Models\User;
use App\Services\AuthenticationService;
use App\Support\PendingUserRegistration;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PendingUserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testSetUsername()
    {
        $email = 'user@example.com';

        $registration = new PendingUserRegistration($email);

        $registration->setUsername('testuser');

        $this->assertEquals('testuser', $registration->username);
    }

    public function testSetPassword()
    {
        $email = 'user@example.com';

        $registration = new PendingUserRegistration($email);

        $registration->setPassword('password123');

        $this->assertTrue(Hash::check('password123', $registration->password));
    }

    public function testExecuteThrowsExceptionForAnExistingUser()
    {
        $this->expectException(AbbeyException::class);

        $this->expectExceptionMessage('User already exists');

        $existingUser = User::factory()->create([
            'email' => 'user@example.com'
        ]);

        $registration = new PendingUserRegistration($existingUser->email);

        $registration->execute();
    }

    public function testExecuteRegistersNewUserCorrectly()
    {
        $email = 'newuser@example.com';

        $registration = new PendingUserRegistration($email);

        $registration->setUsername('newuser')
            ->setPassword('password123')
            ->setEmailVerifiedAt(Carbon::now());

        $user = $registration->execute();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('newuser', $user->username);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertEquals($email, $user->email);
    }

}
