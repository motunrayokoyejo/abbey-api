<?php

namespace Tests\Unit\Support;

use App\Enum\AccountType;
use App\Models\User;
use App\Support\PendingAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendingAccountTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected PendingAccount $pendingAccount;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->pendingAccount = new PendingAccount($this->user);
    }

    public function testSetAccountType()
    {
        $accountType = AccountType::SAVINGS;

        $this->pendingAccount->setAccountType($accountType);

        $this->assertEquals($accountType, $this->pendingAccount->accountType);
    }

    public function testSetNumber()
    {
        $accountNumber = '12345678901';

        $this->pendingAccount->setNumber($accountNumber);

        $this->assertEquals($accountNumber, $this->pendingAccount->number);
    }

    public function testSetBank()
    {
        $bank = 'GTBank';

        $this->pendingAccount->setBank($bank);

        $this->assertEquals($bank, $this->pendingAccount->bank);
    }

    public function testSetBalance()
    {
        $balance = 1000;

        $this->pendingAccount->setBalance($balance);

        $this->assertEquals($balance, $this->pendingAccount->balance);
    }

    public function testBuildAccountAttribute()
    {
        $this->pendingAccount->setAccountType(AccountType::SAVINGS);
        $this->pendingAccount->setNumber('12345678901');
        $this->pendingAccount->setBank('GTBank');
        $this->pendingAccount->setBalance(1000);
        $this->pendingAccount->setMetadata(['key' => 'value']);
        $attributes = $this->pendingAccount->buildAccountAttribute();

        $this->assertEquals([
            'uuid' => $this->pendingAccount->uuid,
            'user_id' => $this->user->id,
            'account_type' => AccountType::SAVINGS,
            'number' => '12345678901',
            'bank' => 'GTBank',
            'balance' => 1000,
            'metadata' => ['key' => 'value'],
        ], $attributes);
    }

    public function testExecute()
    {
        $user = User::factory()->create();

        $account = (new PendingAccount($user))
                    ->setAccountType(AccountType::SAVINGS)
                    ->setNumber('1234567890')
                    ->setBalance(1000)
                    ->setBank('GTBank')
                    ->execute();

        self::assertNotNull($account);

        self::assertEquals('1234567890', $account->number);

        self::assertEquals('GTBank', $account->bank);

        self::assertEquals(1000, $account->balance);

        self::assertEquals(AccountType::SAVINGS, $account->account_type);
    }
}
