<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::check() && Auth::user()->role_id == 2 && Auth::user()->status == 1) //? if user isn't admin and not-active log him out
        {
            Auth::logout();
            return redirect()->route('login')->with("disactivate","You're account is not-active return to management");
        }
        else
            return view('dashboard');
    }
}
