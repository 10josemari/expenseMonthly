<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Una vez logueados, vamos a la vista home
     */
    public function index()
    {
        return view('home.home');
    }
}
