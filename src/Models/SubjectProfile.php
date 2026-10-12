<?php

namespace Saccharine\Operations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SubjectProfile extends Model
{
    use HasUuids;

    protected $guarded = [];
    
    protected $casts = [
        'date_of_birth' => 'date',
        'ssn_sin_verified' => 'boolean',
    ];

    // Point back to the parent
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}
