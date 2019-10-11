<?php

namespace App\Http\Controllers\Super;

use App\Entities\Customer;
use App\Events\AkceptNewCustomer;
use App\Helpers\CustomHelp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;

class CustomersController extends Controller
{

    private $customer;

    public function __construct(Repositories\CustomerRepositoryEloquent $customer){

        $this->middleware('super', ['except'=>['akceptCustomerRegisterByEmail']]);
        $this->customer = $customer;

    }

    public function indexAction(){

        $content = view('super.customer.content');

        return view('super.master', [
            'content'=>$content,
            'title'=>'AHM - Super Administrator - Użytkownicy',
            'controller'=>'admin/super/customers.controller.js'
        ]);

    }


    public function getCustomers(Request $request){

        return $this->customer->searchByCriteria($request->all());

    }

    public function akceptRegister(Request $request){

        $customer = $this->customer->find($request->get('cid'));
        $this->customer->update(['status'=>$request->get('number')],$request->get('cid'));
        event(new AkceptNewCustomer($customer));

        return 1;
    }



    public function akceptCustomerRegisterByEmail($cid,$token){

        $c = Customer::find($cid);

        if(CustomHelp::checkCustomerAkceptToken($token, $c)){

            switch($c->status){

                case '-1':

                    return 'user nie zweryfikował swojej rejestracji';

                    break;

                case '0':

                    Customer::unguard();
                    $this->customer->update(['status'=>1], $cid);
                    event(new AkceptNewCustomer($this->customer->find($cid)));

                    return 'ok';

                    break;


                case '1':

                    return 'user jest już zweryfikowany';

                    break;

            }

        }else{

            return 'bad';

        }

    }


}
