<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'totalAmount',
        'id_status',
        'id_user',
    ];

    /**
     * Get the status of the order.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'id_status');
    }

    /**
     * Get the user that placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
