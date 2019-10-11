<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name'=>'Robert',
            'surname'=>'Radecki',
            'email'=>'raphaelmaj@gmail.com',
            'password'=>bcrypt('wiliak100'),
            'status'=>1,
            'permission'=>'super'
        ]);

        \App\User::create([
            'name'=>'Robert',
            'surname'=>'Radecki',
            'email'=>'r.radecki@dsh.waw.pl',
            'password'=>bcrypt('wiliak100'),
            'status'=>1,
            'permission'=>'super'
        ]);

        \App\User::create([
            'name'=>'Rafał',
            'surname'=>'Majewski',
            'email'=>'erafal.majewski@gmail.com',
            'password'=>bcrypt('wiliak100'),
            'status'=>1,
            'permission'=>'employee'
        ]);

        \App\User::create([
            'name'=>'Robert',
            'surname'=>'Radecki',
            'email'=>'robert@zbiglem.pl',
            'password'=>bcrypt('wiliak100'),
            'status'=>1,
            'permission'=>'employee'
        ]);


//        factory(App\User::class, 20)->create();
    }
}
