<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\User;
use App\Role;
use App\Backend_functions;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Middleware\Authenticate;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerifyAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $role = $request->user()->role;
        $permissions = $role->permissions;
        $actionName = class_basename($request->route()->getActionname());
        foreach ($permissions as $permission)
        {
            $_namespaces_chunks = explode('\\', $permission->controller);
            $controller = end($_namespaces_chunks);
            if ($actionName == $controller . '@' . $permission->method)
            {
                // authorized request
                return $next($request);
            }
        }
        // none authorized request
        return redirect()->back();

    }
}
