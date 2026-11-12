<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
   /* reset je wachtwoord
   */

    use ResetsPasswords;
    protected $redirectTo = '/home';
}
