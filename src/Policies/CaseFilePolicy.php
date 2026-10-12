<?php

declare(strict_types=1);

namespace Saccharine\Operations\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Saccharine\Operations\Models\CaseFile;
use Illuminate\Auth\Access\HandlesAuthorization;

class CaseFilePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CaseFile');
    }

    public function view(AuthUser $authUser, CaseFile $caseFile): bool
    {
        return $authUser->can('View:CaseFile');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CaseFile');
    }

    public function update(AuthUser $authUser, CaseFile $caseFile): bool
    {
        return $authUser->can('Update:CaseFile');
    }

    public function delete(AuthUser $authUser, CaseFile $caseFile): bool
    {
        return $authUser->can('Delete:CaseFile');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CaseFile');
    }

    public function restore(AuthUser $authUser, CaseFile $caseFile): bool
    {
        return $authUser->can('Restore:CaseFile');
    }

    public function forceDelete(AuthUser $authUser, CaseFile $caseFile): bool
    {
        return $authUser->can('ForceDelete:CaseFile');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CaseFile');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CaseFile');
    }

    public function replicate(AuthUser $authUser, CaseFile $caseFile): bool
    {
        return $authUser->can('Replicate:CaseFile');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CaseFile');
    }

}