<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
class AdminsTableSeeder extends Seeder
{
    public function run()
    {
    	DB::table('admins')->truncate();
    	if(DB::table('admins')->count()==0){
	        DB::table('admins')->insert([
	            'name' => 'uanalyze',
	            'password' => Hash::make('57f0a7008635d0a0fe7a6c9335185020'),
	            'auth' => 0
	        ]);
     	}
    }
}
