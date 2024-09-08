<?php

namespace App\Services;

use App\Exceptions\AbbeyException;
use App\Models\Account;
use App\Models\User;
use App\Support\PendingAccount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountService
{
    public static function create(User $user): PendingAccount
    {
        return (new PendingAccount($user))
                ->setNumber(generateAccountNumber())
                ->setBalance(0.00)
                ->setBank(getRandomBank());
    }

    public static function search(User $user): LengthAwarePaginator
    {
        return Account::query()
            ->where('user_id', $user->id)
            ->oldest()
            ->paginate(perPage: 10, page: 1);
    }

    public static function view(User $user, string $id): ?Account
    {
       $account =  Account::query()
                    ->where('user_id', $user->id)
                    ->where('uuid', unmaskId($id))
                    ->first();

       throw_if($account === null, new AbbeyException('Account not found.', 404));

       return $account;
    }

}
