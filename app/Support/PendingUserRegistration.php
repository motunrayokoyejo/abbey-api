<?php

namespace App\Support;

use App\Exceptions\AbbeyException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PendingUserRegistration
{
    public ?string $username = null;

    public ?string $password = null;

    public ?\DateTime $emailVerifiedAt = null;

    public function setUsername(string $username): self
    {
        $this->username = $username;

         return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = Hash::make($password);

        return $this;
    }

    public function setEmailVerifiedAt(\DateTime $emailVerifiedAt): self
    {
        $this->emailVerifiedAt = $emailVerifiedAt;

        return $this;
    }
    public function __construct(protected $email)
    {}

    public function buildUserAttributes(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
            'email' => $this->email,
            'email_verified_at' => $this->emailVerifiedAt,
        ];
    }


    public function execute(): User
    {
        throw_if(User::query()->where('email', $this->email)->exists(),
            (new AbbeyException('User already exists'))
        );

        return User::firstOrCreate(['email' => $this->email], $this->buildUserAttributes());
    }
}
