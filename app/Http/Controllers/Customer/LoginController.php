<?php

namespace App\Http\Controllers\Customer;

use App\ConfigClasses\CustomerAuthConfig;
use App\Entities\Customer;
use App\Events\ResetCustomerPassword;
use App\Events\VerificationNewCustomer;
use App\Jobs\RemeberVerifyByEmail;
use App\Jobs\RemoveNotVeryficationCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Repositories;

class LoginController extends Controller
{

    private $customer;
    private $sonda;

    function __construct(Repositories\CustomerRepositoryEloquent $customer, Repositories\SondaRepositoryEloquent $sonda)
    {
        $this->customer = $customer;
        $this->sonda = $sonda;
        $this->middleware('customer', ['except' => ['showAuthForms', 'checkIsEmailFree', 'registerCustomer', 'verifyCustomer', 'loginCustomer','checkReCaptcha','rememberPassword', 'resetPassword', 'checkIsEmailCanReset', 'changePassword']]);
        $this->middleware('frontauth', ['except' => ['logout']]);

    }


    public function showAuthForms(Request $request, CustomerAuthConfig $cusconfig){

        $bc = [
            [
                'name'=>'Strona główna',
                'active'=>false,
                'url'=>'/'
            ],
            [
                'name'=>'Autoryzacja',
                'active'=>true
            ]
        ];

        $is_intent = false;

        $link = null;


//        if($request->get('_token')){
//
//            $link = $request->get('intent_uri');
//            $is_intent=true;
//
//        }elseif($request->session()->has('intent_uri')){
//
//            $link = $request->session()->get('intent_uri');
//            $is_intent=true;
//
//        }


        if($request->get('intent')){
            $link = $request->get('intent');
            $is_intent=true;

        }
        $hash = '';
        if($request->get('hash')){
            $hash .= '#/';
            $hash_object = \GuzzleHttp\json_decode($request->get('hash'));
            $hash .= (!is_null($hash_object->route))?$hash_object->route:'';

            $i=0;
            $params_route = '';
            foreach($hash_object as $k=>$v) {

                if ($k != 'route') {

                    if ($i != 0) {
                        $params_route .= '&';
                    } else {
                        $params_route = '?';
                    }


                    $params_route .= $k . '=' . $v;

                    $i++;
                }
            }

            $hash .= $params_route;

        }

        return view('front.auth.master', [
            'title'=>'Autoryzacja',
            'controller'=>'front/auth.controller.js',
            'fields'=>$cusconfig->inputs,
            'intent_link'=>($is_intent)?$link:null,
            'intent_hash'=>$hash,
            'frase'=>$request->get('frase'),
            'captcha'=>config('services')['recaptcha'],
            'breadcrumbs'=>$bc
        ]);

    }




    public function checkIsEmailFree(Request $request){

        if(Customer::where('email', $request->get('email'))->count()<1){

            return response(1, 200);

        }else{

            return response(2, 200);

        }


    }

    public function registerCustomer(Request $request){


        $customer = $this->customer->newCustomerCreate($request->all());


        //job for rember customer to click verifiy link
        $job1 = (new RemeberVerifyByEmail($customer))->delay(Carbon::now()->addHours(36));
        // job to delete customer if status -1 not verify
        $job2 = (new RemoveNotVeryficationCustomer($customer))->delay(Carbon::now()->addHours(48));

        dispatch($job1);
        dispatch($job2);

        return response($customer, 200);


    }


    public function verifyCustomer($token){

        $customer = $this->customer->findWhere(['verification_token'=>$token, 'status'=>-1]);

        if($customer->count()>0){
            $this->customer->update(['verification_token'=>'', 'status'=>0], $customer->first()->id);
            event(new VerificationNewCustomer(Customer::find($customer->first()->id)));
            $was_verify = false;
        }else{
            $was_verify = true;
        }

        return view('front.auth.verify.info',[
            'title'=>'Potwierdzenie weryfikacji',
            'controller'=>null,
            'was_verify'=>$was_verify
        ]);

    }


    public function loginCustomer(Request $request){

        if(Auth::guard('customer')->attempt(['email' => $request->get('email'), 'password' => $request->get('password'), 'status' => 1], $request->get('remember'))){
            return 1;
        }
        return 0;

    }


    public function logout(){

        Auth::guard('customer')->logout();

        return redirect('/');

    }


    public function checkReCaptcha(Request $request){

        $secret_key = config('services')['recaptcha']['secret_key'];

        if($request->get('cresponse')==''){
            return 'set_captcha';
        }else{
            $cresponse = $request->get('cresponse');
            $verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$cresponse}");
            $captcha_success = json_decode($verify);

            if($captcha_success->success==true){
                return 'captcha_ok';
            }elseif($captcha_success->success==false){
                return 'bad_captcha';
            }

        }



        return $request->get('cresponse');

    }


    public function rememberPassword(){

        $data = view('front.auth.remember.reset',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false,
        ]);


        return view('front.auth.masterpass', [

            'content'=>$data,
            'title'=>'AHM',
            'controller'=>'front/passwordreset.controller.js'

        ]);

    }

    public function checkIsEmailCanReset(Request $request){

        Customer::unguard();

        $cust = $this->customer->findWhere([
            'email'=>$request->get('email'),
            'status'=>1
        ])->first();

        if(count($cust)<1){
            return 0;
        }else{

            $token = str_slug(bcrypt($cust->email.$request->ip().date('d.m.Y')));
            $this->customer->update([
                    'remember_token'=>$token,
                    'expire_remember_token'=>Carbon::now()->addHour(12)
            ],
                $cust->id
            );

            event(new ResetCustomerPassword($this->customer->find($cust->id)));

            return 1;
        }


    }


    public function resetPassword($token){

        $cust = $this->customer->findWhere(['remember_token'=>$token])->first();


        if(count($cust)>0){


            $ex = explode(' ',$cust->expire_remember_token);
            $dt = explode('-',$ex[0]);
            $tm = explode(':',$ex[1]);

            $exp = Carbon::create($dt[0],$dt[1],$dt[2],$tm[0],$tm[1],$tm[2]);

            if(Carbon::now()->gte($exp)){
                $data = view('front.auth.reset.tokenexpire',[

                ]);
            }else{
                $data = view('front.auth.reset.changepassword',[
                    'cid'=>$cust->id,
                    'token'=>$token
                ]);
            }


        }else{

            $data = view('front.auth.reset.tokenexpire',[

            ]);

        }

        return view('front.auth.masterpass', [

            'content'=>$data,
            'title'=>'AHM',
            'controller'=>'front/passwordreset.controller.js'

        ]);

    }

    public function changePassword(Request $request){

        Customer::unguard();

        $ct = $this->customer->findWhere([
            'id'=>$request->get('id'),
            'remember_token'=>$request->get('token')
        ])->first();


        if(count($ct)>0){

            $this->customer->update([
                'remember_token'=>'',
                'expire_remember_token'=>null,
                'password'=>bcrypt($request->get('password'))
            ], $ct->id);

            return 1;

        }else{
            return 0;
        }


    }


}
