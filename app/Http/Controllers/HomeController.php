<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
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
        {
            $teachers = User::where("role_id",2)->count(); //? displaying number of teachers

            //? displaying number of registeration requests (in waiting list [status=>2])
            $register_requests = User::where("role_id",2)->where("status",2)->count();

            $students = Student::count(); //? displaying number of students


            $teacher_students = Student::where("teacher_id",Auth::id())->count(); //? displaying number of students

            return view('dashboard',
                compact("teachers","students","register_requests","teacher_students"));
        }
    }
}
