<?php

namespace Saccharine\Operations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CaseContact extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'is_sync_ignored' => 'boolean',
    ];
    
    // Point back to the parent
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}
