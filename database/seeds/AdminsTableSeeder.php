<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
class AdminsTableSeeder extends Seeder
{
    public function run()
    {
    	if(DB::table('admins')->count()==0){
	        DB::table('admins')->insert([
	            'name' => 'uanalyze',
	            'password' => Hash::make('123456'),
	            'auth' => 0
	        ]);
     	}
    }
}
