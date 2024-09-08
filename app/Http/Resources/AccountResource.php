<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => maskId($this->uuid),
            'account_type' => $this->account_type,
            'number' => $this->number,
            'bank' => $this->bank,
            'balance' => $this->balance
        ];
    }

}
