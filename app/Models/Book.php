<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasUuids;

    protected $fillable = [
        'title',
        'description',
        'isbn',
        'price',
        'stock',
        'coverUrl',
        'publicationDate',
        'id_author',
    ];

    /**
     * Get the author that wrote the book.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'id_author');
    }

    /**
     * Get the categories for the book.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_categories', 'id_book', 'id_category')
                    ->withTimestamps();
    }
}
