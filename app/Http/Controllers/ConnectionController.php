<?php

namespace App\Http\Controllers;

use App\Enum\Status;
use App\Http\Resources\ConnectionResource;
use App\Services\ConnectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class ConnectionController extends Controller
{

    public function sendFollowerRequest(Request $request): JsonResponse
    {
        $request->validate([
            'follower_id' => ['required', 'string'],
            'status' => ['nullable', new Enum(Status::class)],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $pendingConnection = ConnectionService::makeConnection($user)
                        ->setFollower($request->input('follower_id'));


       if ($request->has('status')) {
           $pendingConnection->setStatus($request->input('status'));
       }

       $newConnection = $pendingConnection->execute();

        return response()->json(new ConnectionResource($newConnection));
    }

    public function decideOnRequest(Request $request): JsonResponse
    {
        $request->validate([
            'connection_id' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $status = Status::from($request->input('status'));

        $updateStatus = ConnectionService::updateConnectionStatus($user,
            $request->input('connection_id'), $status);

        return response()->json(new ConnectionResource($updateStatus));
    }

    public function viewFollowers(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $followers = ConnectionService::search($user);

        return response()->json(ConnectionResource::collection($followers));
    }

}
