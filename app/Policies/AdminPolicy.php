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

    public function permission(Admin $admin, $url)
    {
        $role = $admin->role;
        $permissions = $role->permissions;
        $route = app('router')->getRoutes()->match(app('request')->create($url));
        $actionName = class_basename($route->getActionname());
        foreach ($permissions as $permission)
        {
            $_namespaces_chunks = explode('\\', $permission->controller);
            $controller = end($_namespaces_chunks);
            if ($actionName == $controller . '@' . $permission->method)
            {
                // authorized request
                return true;
            }
        }
        return false;
    }
}
