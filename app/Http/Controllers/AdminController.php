<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        $this->middleware("is_admin");
    }

    //Todos: displaying teachers registration requests
    public function register_requests()
    {
        if (auth()->user()->role_id == 1 && auth()->user()->status == 3) //? if user is admin & his status is active
        {
            //? displaying just waiting teachers & showing them from the newest to oldest
            $teachers = User::where("status",2)->orderBy('created_at','desc')->paginate(5);
            return view("Admin.register_requests",compact("teachers"));
        }
        else
        {
            auth()->logout();
            return redirect()->route('login')->with("disactivate","You're account is not-active return to management");
        }
    }

    //Todos: this function for letting admin can accept teachers register request by changing status
    public function accept_register_request($id)
    {
        $teacher = User::findOrfail($id);

        $teacher -> update([
            "status" => 3
        ]);

        // Mail::to($user->email)->send(new RegisterRequestMail($user));

        return redirect()->route("admin.register_requests")->with("Registeration_request","Request accepted successfully");
    }

    //Todos: this function for letting admin can refuse teachers register request by changing status
    public function refuse_register_request($id)
    {
        $teacher = User::findOrfail($id);

        $teacher -> update([
            "status" => 1
        ]);

        // Mail::to($user->email)->send(new RegisterRequestMail($user));

        return redirect()->route("admin.register_requests")->with("Registeration_request","Request refused successfully");
    }

    //Todos: this function for letting admin seeing users for control on them (active,not-active)
    public function admin_control()
    {
        if (auth()->user()->role_id == 1 && auth()->user()->status == 3) //? if user is admin & his status is active
        {
            $teachers = User::where('role_id',2)->paginate(5); //? just seeing users (not admins)
            return view('Admin.admin_control',compact('teachers'));
        }
        else
        {
            auth()->logout();
            return redirect()->route('login')->with("disactivate","You're account is not-active return to management");
        }
    }

    //Todos: this function for letting admin has control on activation
    public function activation_control($id)
    {
        $teacher = User::findOrfail($id);
        //? if user is not-active (status = 1) let him active (status = 3)
        if($teacher->status == 1)
        {
            $teacher -> update([
                "status" => 3
            ]);
            session()->flash('activation', 'User activated successfully!');
        }
        //? if user is active (status = 3) let him not-active (status = 1)
        elseif($teacher->status == 3)
        {
            $teacher -> update([
                "status" => 1
            ]);
            session()->flash('activation', 'User deactivated successfully!');
        }
        return redirect()->route("admin.admin_control");
    }
}
