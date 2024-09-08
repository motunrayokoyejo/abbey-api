<?php

namespace App\Support;

use App\Enum\Status;
use App\Exceptions\AbbeyException;
use App\Models\Connection;
use App\Models\User;
use Ramsey\Uuid\Uuid;

class PendingConnection
{
    public ?User $follower = null;

    public ?Status $status = Status::PENDING;

    public ?string $uuid = null;
    public function __construct(protected User $user)
    {}

    public function setFollower(string $followerId): static
    {
        $follower = User::where('uuid', unmaskId($followerId))->first();

        throw_if($follower === null,
            (new AbbeyException('Invalid user, please try again')));

        $this->follower = $follower;

        return $this;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function buildFollowerAttribute(): array
    {

        return [
            "uuid" => $this->uuid,
            "user_id" => $this->user->id,
            "follower_id" => $this->follower->id,
            "status" => $this->status
        ];
    }

    public function execute(): Connection
    {
        $existingConnection = Connection::where('user_id', $this->user->id)
            ->where('follower_id', $this->follower->id)
            ->first();

        if ($existingConnection) {
          throw new AbbeyException('Connection already exists');
        }

       return Connection::create($this->buildFollowerAttribute());
    }

}
