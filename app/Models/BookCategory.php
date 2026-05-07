<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookCategory extends Pivot
{
    use HasUuids;

    protected $fillable = [
        'id_category',
        'id_book',
    ];
}
