<?php
namespace App\Repositories;

use App\Admin;
use Hash;
class AdminRepository extends Repository
{
    public function __construct(Admin $admin)
    {
        $this->model = $admin;
    }

   	public function check($credentials)
    {
      $admin_data = $this->model->where('name',$credentials['name'])->first();
      if($admin_data && Hash::check($credentials['password'],$admin_data->password))
      {
        if(Hash::needsRehash($admin_data->password)){
          $hashed = Hash::make($credentials['password']);
          update_password($admin_data->id,$hashed);
        }
        return true;
      }
      else
      {
        return false;
      }
   	}
    protected function update_password($id,$hashpwd){
        $this->model->where('id',$id)->update(['password'=>$hashpwd]);
    }
}