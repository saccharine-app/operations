<?php

namespace Saccharine\Operations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffMember extends Model
{
    use HasUuids;

    protected $fillable = [
        'first_name',
        'last_name',
        'professional_title',
        'is_active',
        'user_id',
    ];
    
    // Casts ensure the database boolean is treated as a true PHP boolean
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Establishes the relationship so you can call $staff->user
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // A virtual attribute so Filament can easily display "First Last" in dropdowns
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
