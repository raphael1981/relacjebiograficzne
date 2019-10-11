<?php

namespace App\Helpers;


use Illuminate\Support\Facades\Auth;

class AuthAjaxCheck{

    public $auth = [];

    public function __construct(){

        if(Auth::guard()->check()){

            $this->auth['type']='admin';
            $this->auth['boolean']=true;

        }elseif(Auth::guard('customer')->check()){

            $this->auth['type']='customer';
            $this->auth['boolean']=true;

        }else{

            $this->auth['type']=null;
            $this->auth['boolean']=false;

        }
    }


}

