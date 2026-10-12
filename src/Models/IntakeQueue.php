<?php

namespace Saccharine\Operations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Import UUID trait

class IntakeQueue extends Model
{
    use HasUuids; // Tell the model to generate UUIDs automatically

    // Define which fields are allowed to be saved to the database
    protected $fillable = [
        'status',
        'submitted_name',
        'contact_phone',
        'contact_email',
        'raw_payload',
        'source_channel',
    ];

    // Automatically cast the JSONB column to a PHP array when reading
    protected $casts = [
        'raw_payload' => 'array',
    ];
}
