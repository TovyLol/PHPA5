<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class ConfirmPasswordController extends Controller
{
    /*
    Controller voor passwords bevestiging
    meer om te confirmen of het echt de user is die de acties maakt
    */

    use ConfirmsPasswords;

    /* failsafe
    heb geen zin om het anders te doen dus stuur ze maar naar het begin scherm
    */
    protected $redirectTo = '/home';
    /* authenticate de user voor de acties worden genomen
    */
    public function __construct()
    {
        $this->middleware('auth');
    }
}
