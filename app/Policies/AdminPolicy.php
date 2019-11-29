<?php

namespace App\Policies;

use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    const PERMISSION = 'permission';

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function permission(Admin $admin, $controller, $method)
    {
        $role = $admin->role;
        $permissions = $role->permissions;
        foreach ($permissions as $permission)
        {
            $_namespaces_chunks = explode('\\', $permission->controller);
            $route_controller = end($_namespaces_chunks);
            if ($controller == $route_controller && $method == $permission->method)
            {
                // authorized request
                return true;
            }
        }
        return false;
    }
}
