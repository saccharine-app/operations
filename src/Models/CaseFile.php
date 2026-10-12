<?php

namespace Saccharine\Operations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CaseFile extends Model
{
    use SoftDeletes;
    use HasUuids;

    protected $fillable = [
        'status',
        'assigned_staff_id',
    ];

    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class, 'assigned_staff_id');
    }

    // Define the relationship with SubjectProfile
    public function subjectProfiles(): MorphOne
    {
        // 'owner' tells Laravel to look for owner_type and owner_id columns
        return $this->morphOne(SubjectProfile::class, 'owner'); 
    }
    
    // Define the relationship with CaseContact
    public function caseContacts(): MorphMany
    {
        // 'owner' tells Laravel to look for owner_type and owner_id columns
        return $this->morphMany(CaseContact::class, 'owner'); 
    }
}
