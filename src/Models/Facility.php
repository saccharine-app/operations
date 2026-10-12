<?php

namespace Saccharine\Operations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use HasUuids;

    protected $fillable = [
        'type',
        'name',
        'parent_facility_id',
        'address_street',
        'address_city',
        'address_state_province',
        'address_country',
        'address_postal_code',
        'phone_number',
        'license_number',
        'extended_meta',
        'is_active',
    ];

    protected $casts = [
        'extended_meta' => 'array',
        'is_active' => 'boolean',
    ];

    // The facility this location belongs to (e.g., The Cemetery)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'parent_facility_id');
    }

    // Facilities located inside this one (e.g., The Crematoriums)
    public function children(): HasMany
    {
        return $this->hasMany(Facility::class, 'parent_facility_id');
    }
}
