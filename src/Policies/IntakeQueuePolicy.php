<?php

declare(strict_types=1);

namespace Saccharine\Operations\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Saccharine\Operations\Models\IntakeQueue;
use Illuminate\Auth\Access\HandlesAuthorization;

class IntakeQueuePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:IntakeQueue');
    }

    public function view(AuthUser $authUser, IntakeQueue $intakeQueue): bool
    {
        return $authUser->can('View:IntakeQueue');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:IntakeQueue');
    }

    public function update(AuthUser $authUser, IntakeQueue $intakeQueue): bool
    {
        return $authUser->can('Update:IntakeQueue');
    }

    public function delete(AuthUser $authUser, IntakeQueue $intakeQueue): bool
    {
        return $authUser->can('Delete:IntakeQueue');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:IntakeQueue');
    }

    public function restore(AuthUser $authUser, IntakeQueue $intakeQueue): bool
    {
        return $authUser->can('Restore:IntakeQueue');
    }

    public function forceDelete(AuthUser $authUser, IntakeQueue $intakeQueue): bool
    {
        return $authUser->can('ForceDelete:IntakeQueue');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:IntakeQueue');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:IntakeQueue');
    }

    public function replicate(AuthUser $authUser, IntakeQueue $intakeQueue): bool
    {
        return $authUser->can('Replicate:IntakeQueue');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:IntakeQueue');
    }

}