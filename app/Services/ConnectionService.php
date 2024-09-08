<?php

namespace App\Services;

use App\Enum\Status;
use App\Exceptions\AbbeyException;
use App\Models\Connection;
use App\Models\User;
use App\Support\PendingConnection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ConnectionService
{
    public static function makeConnection(User $user): PendingConnection
    {
        return (new PendingConnection($user));
    }

    public static function updateConnectionStatus(User $user,
        string $connectionId, Status $status): Connection
    {
        $connection = Connection::where('user_id', $user->id)
                            ->where('uuid', unmaskId($connectionId))->first();

        throw_if($connection === null,
            (new AbbeyException('Invalid relationship', 403)));

        $connection->status = $status;

        $connection->save();

        return $connection;
    }

    public static function search(User $user): LengthAwarePaginator
    {
        return Connection::query()
                        ->where('user_id', $user->id)
                        ->oldest()
                        ->paginate(perPage: 10, page: 1);
    }

}
