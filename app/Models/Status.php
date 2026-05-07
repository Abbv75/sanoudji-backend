<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'status';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the orders with this status.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_status');
    }
}
