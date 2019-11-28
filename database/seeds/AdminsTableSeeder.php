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
                [
                   'name' => 'shouwda@uanalyze.com.tw',
                   'password' => Hash::make('123456'),
                   'auth' => 3
                ],
                [   'name' => 'kfp87198@uanalyze.com.tw',
                    'password' => Hash::make('123456'),
                    'auth' => 3
                ],
                [   'name' => 'seantu@uanalyze.com.tw',
                    'password' => Hash::make('123456'),
                    'auth' => 3
                ],
                [   'name' => 'teresa@uanalyze.com.tw',
                    'password' => Hash::make('123456'),
                    'auth' => 3
                ],
                [   'name' => 'hankshih@uanalyze.com.tw',
                    'password' => Hash::make('123456'),
                    'auth' => 3
                ],
                [   'name' => 'joechen@uanalyze.com.tw',
                    'password' => Hash::make('123456'),
                    'auth' => 3
                ],
                [   'name' => 'cyc2016@uanalyze.com.tw',
                    'password' => Hash::make('123456'),
                    'auth' => 3
                ],
                [   'name' => 'joshhuang@uanalyze.com.tw',
                    'password' => Hash::make('123456'),
                    'auth' => 1
                ],
                [   'name' => 'bubuyen@uanalyze.com.tw',
                    'password' => Hash::make('123456'),
                    'auth' => 3
                ],
                [   'name' => 'azrae24@uanalyze.com.tw',
                    'password' => Hash::make('123456'),
                    'auth' => 3
                ]
            ]);
     	}
    }
}
