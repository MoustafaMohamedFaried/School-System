<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    if(Auth::check() && Auth::user())
        return view('dashboard');
    else
        return view('Auth.login');
});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//? student controller and remove show,create from resource controller
Route::resource('/student', StudentController::class)->except("show","create");


Route::controller(TeacherController::class)->group(function () {

    //? teachers & admins can access
    Route::get('/teacher', 'index')->name('teacher.index');

    //? access for admins
    Route::post('/teacher/add_teacher', 'add_teacher')->name('teacher.add_teacher');

    Route::delete('/teacher/destroy/{id}', 'destroy')->name('teacher.destroy');

    //? access for teachers (do it for his own data)
    Route::get('/teacher/edit/{id}', 'edit_teacher')->name('teacher.edit_teacher');
    Route::put('/teacher/update/{id}', 'update_teacher')->name('teacher.update_teacher');

});

Route::controller(AdminController::class)->group(function () {

    //? access for just admins
    Route::get('/admin', 'admin_control')->name('admin.admin_control');
    Route::get('/admin/register_requests', 'register_requests')->name('admin.register_requests');
    Route::put('/admin/accept_register_request/{id}', 'accept_register_request')->name('admin.accept_register_request');
    Route::put('/admin/refuse_register_request/{id}', 'refuse_register_request')->name('admin.refuse_register_request');
    Route::put('/admin/activation_control/{id}', 'activation_control')->name('admin.activation_control');

});




//? secure routes from writing at url
Route::fallback(function () {
    abort(404);
});
