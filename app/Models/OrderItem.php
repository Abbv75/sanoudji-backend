<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'quantity',
        'unitPrice',
        'totalPrice',
        'id_order',
        'id_book',
    ];

    /**
     * Get the order that contains this item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_order');
    }

    /**
     * Get the book associated with this item.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'id_book');
    }
}
