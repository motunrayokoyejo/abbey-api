<?php

namespace App\Models;

use App\Enum\AccountType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Account
 *
 * @property int        id
 * @property string     uuid
 * @property  int       user_id
 * @property  AccountType    account_type
 * @property string     number
 * @property string     bank
 * @property int        balance
 * @property array      metadata
 * @property \Carbon\Carbon|null deleted_at
 * @property \Carbon\Carbon|null updated_at
 * @property \Carbon\Carbon|null created_at
 */
class Account extends Model
{
    use HasFactory;
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    protected $casts = [
        'account_type' => AccountType::class,
        'metadata' => 'array',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'number',
        'bank',
        'user_id',
        'account_type',
        'metadata',
        'balance',
        'created_at',
        'updated_at',
    ];
}
