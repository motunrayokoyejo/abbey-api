<?php

namespace App\Models;

use App\Models\Contracts\ResourceIdentifierInterface;
use App\Models\Traits\HasResourceIdentifier;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PersonalAccessToken.
 *
 * @property int                      id
 * @property string                   uuid
 * @property int|null                 tokenable_id
 * @property string                   tokenable_type
 * @property string                   name
 * @property string                   token
 * @property array                    abilities
 * @property \Carbon\Carbon           last_used_at
 * @property \Carbon\Carbon|null      expires_at
 * @property \Carbon\Carbon           updated_at
 * @property \Carbon\Carbon           created_at
 * @property CustomerUser tokenable
 */
class PersonalAccessToken extends \Laravel\Sanctum\PersonalAccessToken
{
    protected $casts = ['last_used_at' => 'datetime', 'expires_at' => 'datetime', 'abilities' => 'array'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'name',
        'token',
        'abilities',
        'expires_at',
        'last_used_at',
    ];
}
