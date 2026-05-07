<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetadataAttribute extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'dataType',
    ];

    /**
     * Get the metadata values for this attribute.
     */
    public function bookMetadata()
    {
        return $this->hasMany(BookMetadata::class, 'id_metadata_attribute');
    }
}
