<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'biography',
        'profilePhotoUrl',
    ];

    /**
     * Get the books for the author.
     */
    public function books()
    {
        return $this->hasMany(Book::class, 'id_author');
    }
}
