<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BookCategory extends Model
{
    use HasUuids;

    protected $fillable = [
        'id_category',
        'id_book',
    ];
}
