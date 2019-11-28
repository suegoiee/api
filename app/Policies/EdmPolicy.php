<?php

namespace App\Policies;

use App\Edm;
use App\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class EdmPolicy
{
    use HandlesAuthorization;

    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';

    public function create(Admin $admin)
    {
        return $admin->isSuper() || $admin->isManager();
    }

}
