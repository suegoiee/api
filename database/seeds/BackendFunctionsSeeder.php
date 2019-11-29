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
        $manager_controll = ['EdmController', 'ProductController', 'TagController', 'CompanyController', 'MessageController', 'ArticleController', 'PromocodeController', 'NotificationMessageController', 'LoginController'];
        $manager_watch = ['OrderController'];
        $staff_controll = ['MessageController', 'ArticleController', 'PromocodeController', 'NotificationMessageController', 'LoginController'];
        $staff_watch = ['EdmController', 'ProductController', 'TagController', 'CompanyController'];
        foreach (Route::getRoutes()->getRoutes() as $key => $route)
        {
            // get route action
            $action = $route->getActionname();
            // separating controller and method
            $_action = explode('@',$action);
            
            $controller = $_action[0];
            $method = end($_action);
            $_namespaces_chunks = explode('\\', $controller);
            $route_controller = end($_namespaces_chunks);
            
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
                if(in_array($route_controller, $manager_controll)){
                    $manager_permission_ids[] = $permission->id;
                }
                if(in_array($route_controller, $staff_controll)){
                    $staff_permission_ids[] = $permission->id;
                }

                if(in_array($route_controller, $manager_watch) && ($method == 'index' || $method == 'show' || $method == 'export' || $method == 'edit')){
                    $manager_permission_ids[] = $permission->id;
                }
                if(in_array($route_controller, $staff_watch) && ($method == 'index' || $method == 'show' || $method == 'export' || $method == 'edit')){
                    $staff_permission_ids[] = $permission->id;
                }
            }
        }
        // find admin role.
        $admin_role = Role::where('name','master')->first();
        // atache all permissions to admin role
        $admin_role->permissions()->attach($permission_ids);
        
        // find admin role.
        $manager_role = Role::where('name','manager')->first();
        // atache all permissions to admin role
        $manager_role->permissions()->attach($manager_permission_ids);
        
        // find admin role.
        $staff_role = Role::where('name','staff')->first();
        // atache all permissions to admin role
        $staff_role->permissions()->attach($staff_permission_ids);
    }
}
