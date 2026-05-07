<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'description',
        'coverUrl',
    ];

    /**
     * Get the books for the category.
     */
    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_categories', 'id_category', 'id_book')
                    ->withTimestamps();
    }
}
