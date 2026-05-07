<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasUuids;

    protected $fillable = [
        'amount',
        'id_status',
        'id_order',
    ];

    /**
     * Get the status of the payment.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'id_status');
    }

    /**
     * Get the order associated with the payment.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
}
