<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConnectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => maskId($this->uuid),
            'follower_id' => maskId($this->follower->uuid),
            'follower_name' => $this->follower->username,
            'status' => $this->status,
        ];
    }

}
