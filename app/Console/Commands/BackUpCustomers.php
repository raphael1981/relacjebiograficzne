<?php

namespace App\Console\Commands;

use App\Entities\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackUpCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup Customers to external Datebase';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        Customer::unguard();

        foreach(Customer::all() as $key=>$cust){


            if($this->checkIsCustomerInBackUp($cust)==0){

                DB::connection('mysqlbackup')
                    ->table('customers')
                    ->insert([
                        'name'=>$cust->name,
                        'surname'=>$cust->surname,
                        'email'=>$cust->email,
                        'password'=>$cust->password,
                        'phone'=>$cust->phone,
                        'customer_type'=>$cust->customer_type,
                        'institution_name'=>$cust->institution_name,
                        'register_target'=>$cust->register_target,
                        'status'=>$cust->status,
                        'verification_token'=>$cust->verification_token,
                        'remember_token'=>$cust->remember_token,
                        'expire_remember_token'=>$cust->expire_remember_token,
                        'deleted_at'=>$cust->deleted_at,
                        'created_at'=>$cust->created_at,
                        'updated_at'=>$cust->updated_at
                    ]);


                $this->info('New one '.$cust->email);

            }else{
                $this->info('Exist '.$cust->email);
            }

        }


    }


    private function checkIsCustomerInBackUp($c){

        return DB::connection('mysqlbackup')
                ->table('customers')
                ->where('email', $c->email)
                ->count();
    }

}
