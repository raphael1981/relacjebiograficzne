<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FrontController extends Controller
{


    public function __construct(){

        $this->middleware('employee', ['exept'=>[]]);

    }


    public function indexAction(){

        $content = view('employee.employee.content');

        return view('employee.masteremployee', [
            'content' => $content,
            'title' => 'AHM - Employee - Transkrypcje',
            'controller' => 'admin/super/records.controller.js'
        ]);

    }

}
