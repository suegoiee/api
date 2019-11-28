<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('role')->truncate();
        DB::table('role')->insert([
            [   
                'name' => 'master'
            ],[
                'name' => 'manager'
            ],[
                'name' => 'staff'
            ]
        ]);
    }
}
