<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
   // klopt het allemaal wel

    use AuthenticatesUsers;

   
    protected $redirectTo = '/home';

    // als de user ingelogd is dan kan hij uitloggen bla
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
