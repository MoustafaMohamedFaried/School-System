<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\User;

class StudentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('is_admin')->only("store");

        //? this middleware to let request just from ajax to secure json data is user write the url
        $this->middleware('is_ajax')->only("edit","update");
    }

    //Todos: with relation (teacher) displaing for teacher just his own students but for admin displaing all students
    public function index()
    {
        //? if user is teacher & his status is not-active log him out
        if (auth()->user()->role_id == 2 && auth()->user()->status == 1)
        {
            auth()->logout();
            return redirect()->route('login')->with("disactivate","You're account is not-active return to management");
        }

        //? if user is teacher & his status is waiting redirect him to dashboard
        elseif(auth()->user()->role_id == 2 && auth()->user()->status == 2)
        {
            return redirect()->route("home");
        }

        else
        {
            if(auth()->user()->role_id == 1) //? if user is admin
            {
                $teachers = User::where('role_id',2)->get(); //? calling teachers users to use in addning students
                $students = Student::with("teacher")->paginate(5);
                return view("students",compact("students","teachers"));
            }

            //? if user is teacher displaing his own students dependence on his id and teacher_id at students table
            else
            {
                $students = Student::with("teacher")->where('teacher_id',auth()->id())->paginate(5);
                return view("students",compact("students"));
            }
        }
    }


    //Todos: adding student just for admins
    public function store(StoreStudentRequest $request)
    {
        if(auth()->user()->role_id == 1)
        {
            Student::create([
                "name" => $request -> name,
                "age" => $request -> age,
                "teacher_id" => $request -> teacher_id,
            ]);

            return redirect()->route("student.index")->with("Add","Student added successfully");
        }
        else
            abort(401);
    }


    //Todos: showing specfic student to showing it as ajax
    public function edit($id)
    {
        if(auth()->user()->role_id == 1)
            $data = Student::findOrfail($id);
        elseif(auth()->user()->role_id == 2)
            $data = Student::where('teacher_id',auth()->id())->findOrfail($id);
        return response()->json($data);
    }


    public function update(UpdateStudentRequest $request, $id)
    {
        if($request->ajax())
        {
            $student = Student::findOrfail($id);

            $student -> update([
                "name" => $request -> name,
                "age" => $request -> age,
                "updated_at" => date("Y-m-d H:i:s"),
            ]);

            //? Check if add new teacher update it else let it on the old one
            if ($request->has('teacher_id') && !empty($request->teacher_id)) {
                $student -> update([
                    "teacher_id" => $request -> teacher_id,
                ]);
            }

            //? calling teacher name form (teacher) relation at student model
            $teacher_name = $student->teacher->name;

            //Todos: here we pass data as json to be able to do the function without reload page
            return response()->json(['student' => $student, 'teacher_name' => $teacher_name]);
        }
    }


    public function destroy($id)
    {
        $student = Student::findOrfail($id);
        $student->delete();

        //Todos: we pass studentData like the row in blade which named student_id
        return response()->json(['success' => true, 'studentData' => 'student_'.$id]);
    }
}
