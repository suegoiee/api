<?php

use App\Role;
use App\Backend_functions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;

class BackendFunctionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('backend_functions')->truncate();
    	DB::table('role_functions')->truncate();
        $permission_ids = []; // an empty array of stored permission IDs
        // iterate though all routes
        foreach (Route::getRoutes()->getRoutes() as $key => $route)
        {
            // get route action
            $action = $route->getActionname();
            // separating controller and method
            $_action = explode('@',$action);
            
            $controller = $_action[0];
            $method = end($_action);
            
            // check if this permission is already exists
            $permission_check = Backend_functions::where(
                ['controller'=>$controller,'method'=>$method]
            )->first();
            if(!$permission_check){
                $permission = new Backend_functions;
                $permission->controller = $controller;
                $permission->method = $method;
                $permission->save();
                // add stored permission id in array
                $permission_ids[] = $permission->id;
            }
        }
        // find admin role.
        $admin_role = Role::where('name','master')->first();
        // atache all permissions to admin role
        $admin_role->permissions()->attach($permission_ids);
    }
}
