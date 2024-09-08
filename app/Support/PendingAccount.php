<?php

namespace App\Support;

use App\Enum\AccountType;
use App\Models\Account;
use App\Models\User;

class PendingAccount
{

    public ?string $uuid = null;

    public ?AccountType $accountType = null;

    public ?string $number = null;

    public ?string $bank = null;

    public ?int $balance = 0;

    public ?array $metadata = [];

    public function __construct(protected User $user){}

    public function setAccountType(AccountType $accountType): static
    {
        $this->accountType = $accountType;

        return $this;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function setBank(string $bank): static
    {
        $this->bank = $bank;

        return $this;
    }

    public function setBalance(int $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function setMetadata(array $metadata): static
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function buildAccountAttribute(): array
    {
        return [
            'uuid' => $this->uuid,
            'user_id' => $this->user->id,
            'account_type' => $this->accountType,
            'number' => $this->number,
            'bank' => $this->bank,
            'balance' => $this->balance,
            'metadata' => $this->metadata,
        ];
    }

    public function execute(): Account
    {
        return Account::create($this->buildAccountAttribute());

    }

}
