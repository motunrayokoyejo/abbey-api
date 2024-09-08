<?php

namespace Tests\Unit\Service;

use App\Exceptions\AbbeyException;
use App\Models\Account;
use App\Models\User;
use App\Services\AccountService;
use App\Support\PendingAccount;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testCreateReturnsPendingAccount()
    {
        $pendingAccount = AccountService::create($this->user);

        $this->assertInstanceOf(PendingAccount::class, $pendingAccount);
        $this->assertNotNull($pendingAccount->number);
        $this->assertEquals(0.00, $pendingAccount->balance);
        $this->assertNotNull($pendingAccount->bank);
    }

    public function testViewReturnsAccount()
    {
        $account = Account::factory()->create([
            'user_id' => $this->user->id,
            'uuid' => 'test-uuid'
        ]);

        $result = AccountService::view($this->user, maskId('test-uuid'));

        $this->assertInstanceOf(Account::class, $result);
        $this->assertEquals($account->id, $result->id);
    }

    public function testViewThrowsExceptionWhenAccountNotFound()
    {
        $this->expectException(AbbeyException::class);
        $this->expectExceptionMessage('Account not found.');

        AccountService::view($this->user, maskId('non-existent-uuid'));
    }

}
