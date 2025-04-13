<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Traits\HasRoles;

trait HasPermissionsTrait
{
    public function hasPermissionTo($permission)
    {
        if (is_array($permission)) {
            return $this->canAny($permission);
        }

        return $this->can($permission);
    }

    public function authorizeForUser($user, $permission)
    {
        if (is_array($permission)) {
            if (!$user->canAny($permission)) {
                throw new UnauthorizedException();
            }
        } else {
            if (!$user->can($permission)) {
                throw new UnauthorizedException();
            }
        }
    }
}