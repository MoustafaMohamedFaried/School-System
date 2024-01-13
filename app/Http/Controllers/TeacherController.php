<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('is_admin')->only("add_teacher","destroy");
        $this->middleware('is_teacher')->only("edit_teacher","update_teacher");
    }

    //Todos: displaying teachers data for admin but, for teacher display just his own data
    public function index()
    {
        //? if user is active
        if(Auth::user()->status == 3)
        {
            if(Auth::user()->role_id == 1)
                $teachers = User::where('role_id',2)->paginate(5); //? if user is admin display all teachers
            else //? if user is teacher display his own data
                $teachers = User::where('role_id',2)->where('id',Auth::id())->paginate(5);

            return view("teachers",compact("teachers"));
        }

        //? if user is teacher & his status is not-active log him out
        elseif (Auth::user()->role_id == 2 && Auth::user()->status == 1)
        {
            Auth::logout();
            return redirect()->route('login')->with("disactivate","You're account is not-active return to management");
        }

        //? if user is teacher & his status is waiting redirect him to dashboard
        elseif(Auth::user()->role_id == 2 && Auth::user()->status == 2)
        {
            return redirect()->route("home");
        }


    }

    //Todos: adding teacher & give him default pass (123456789) (just by admin)
    public function add_teacher(AddTeacherRequest $request)
    {
        if(Auth::user()->role_id == 1)
        {
            //? when user is admin can add teacher
            User::where('role_id',1)->create([
                "name" => $request -> name,
                "username" => $request -> username,
                "email" => $request -> email,
                "password" => bcrypt(123456789),
                "role_id" => 2, //? give him teacher role
                "status" => 3, //* 1=> not-active 2=>waiting 3=> active
            ]);

            return redirect()->route("teacher.index")->with("Add","Teacher added successfully");
        }
        else
            abort(401);
    }

    //Todos: displaying teacher data dependece on his id (only teacher can edit his data)
    public function edit_teacher($id)
    {
        if(Auth::user()->role_id == 2 && Auth::id() == $id)
        {
            $data = User::where('id',Auth::id())->findOrfail($id);
            return response()->json($data);
        }
        else
            abort(401);
    }

    //Todos: updating teacher data dependece on his id (only teacher can update his data)
    public function update_teacher(UpdateTeacherRequest $request, $id)
    {
        if($request->ajax())
        {
            $teacher = User::where('id',Auth::id())->findOrfail($id);

            $teacher -> update([
                "name" => $request -> name,
                "username" => $request -> username,
                "status" => 3,
                "updated_at" => date("Y-m-d H:i:s"),
            ]);

            //? Check if user add new password update it else let it on the old one
            if ($request->has('password') && !empty($request->password)) {
                $teacher -> update([
                    "password" => bcrypt($request->password)
                ]);
            }

            //? Check if user add new email update it else let it on the old one
            if ($request->has('email') && !empty($request->email)) {
                $teacher -> update([
                    "email" => $request-> email
                ]);
            }

            //Todos: here we pass data as json to be able to do the function without reload page
            return response()->json(['teacher' => $teacher]);
        }
    }

    //Todos: delete teacher (just admin can do it)
    public function destroy($id)
    {
        $teacher = User::where('id',$id)->findOrfail($id);
        $teacher->delete();

        //Todos: we pass teacherData like the row in blade which named teacher_id
        return response()->json(['success' => true, 'teacherData' => 'teacher_'.$id]);
    }

}
