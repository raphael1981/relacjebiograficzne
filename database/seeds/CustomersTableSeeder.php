<?php

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\Entities\Customer::create([
            'name'=>'Rafał',
            'surname'=>'Majewski',
            'email'=>'raphaelmaj@gmail.com',
            'password'=>bcrypt('wiliak100'),
            'phone'=>'999888777',
            'customer_type' => 'instytucja',
            'institution_name' => 'DSH',
            'register_target'=>'artykuły naukowe',
            'status'=>1,
            'verification_token'=>''
        ]);

        \App\Entities\Customer::create([
            'name'=>'Robert',
            'surname'=>'Radecki',
            'email'=>'r.radecki@dsh.waw.pl',
            'password'=>bcrypt('wiliak100'),
            'phone'=>'999888777',
            'customer_type' => 'instytucja',
            'institution_name' => 'DSH',
            'register_target'=>'artykuły naukowe',
            'status'=>1,
            'verification_token'=>''
        ]);

        \App\Entities\Customer::create([
            'name'=>'Rafał',
            'surname'=>'Majewski',
            'email'=>'erafal.majewski@gmail.com',
            'password'=>bcrypt('wiliak100'),
            'phone'=>'999888777',
            'customer_type' => 'instytucja',
            'institution_name' => 'DSH',
            'register_target'=>'artykuły naukowe',
            'status'=>1,
            'verification_token'=>''
        ]);

        \App\Entities\Customer::create([
            'name'=>'Robert',
            'surname'=>'Radecki',
            'email'=>'robert@zbiglem.pl',
            'password'=>bcrypt('wiliak100'),
            'phone'=>'999888777',
            'customer_type' => 'instytucja',
            'institution_name' => 'DSH',
            'register_target'=>'artykuły naukowe',
            'status'=>1,
            'verification_token'=>''
        ]);

//        factory(App\Entities\Customer::class, 33)->create();
    }
}
