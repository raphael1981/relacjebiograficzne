<?php

namespace App\Http\Controllers\Super;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function __construct(){

        $this->middleware('super', ['exept'=>[]]);

    }

    public function indexAction(){

        $content = view('super.category.content');

        return view('super.master', [
            'content'=>$content,
            'title'=>'AHM - Super Administrator - Galerie',
            'controller'=>'admin/super/categories.controller.js'
        ]);

    }
}
