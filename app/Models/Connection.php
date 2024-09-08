<?php

namespace App\Models;

use App\Enum\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * class Follower
 *
 * @property        int id
 * @property        string uuid
 * @property        int user_id
 * @property        int follower_id
 * @property        Status status
 * @property \Carbon\Carbon|null deleted_at
 * @property \Carbon\Carbon|null updated_at
 * @property \Carbon\Carbon|null created_at
 *
 */
class Connection extends Model
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
        'status' => Status::class,
        'metadata' => 'array',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'uuid',
        'user_id',
        'follower_id',
        'metadata',
        'status',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
