<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('auth');
    }

    //laat de home pagina zien als de user ingelogd is
    public function index()
    {
        return view('home');
    }
}
