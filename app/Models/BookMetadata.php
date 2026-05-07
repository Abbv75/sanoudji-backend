<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BookMetadata extends Model
{
    use HasUuids;

    protected $fillable = [
        'value',
        'id_metadata_attribute',
        'id_book',
    ];

    /**
     * Get the attribute that this metadata value belongs to.
     */
    public function attribute()
    {
        return $this->belongsTo(MetadataAttribute::class, 'id_metadata_attribute');
    }
}
