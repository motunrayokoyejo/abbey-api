<?php

namespace App\Http\Controllers;

use App\Enum\AccountType;
use App\Http\Resources\AccountResource;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    public function createAccount(Request $request): JsonResponse
    {
        $request->validate([
            'account_type' => ['required', 'string'],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $accountType = AccountType::tryFrom($request->input('account_type'));

        $createAccount = AccountService::create($user)
                            ->setAccountType($accountType)
                            ->execute();

        return response()->json(new AccountResource($createAccount));
    }

    public function viewAccounts(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $search = AccountService::search($user);

        return response()->json(AccountResource::collection($search));

    }

    public function viewAccount(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        $account = AccountService::view($user, $id);

        return response()->json(new AccountResource($account));
    }

}
