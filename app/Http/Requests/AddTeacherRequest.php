<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => "required|string",
            "email" => "required|email|unique:users,email,except,id",
            "username" => "required|string|unique:users,username,except,id",
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "Please add teacher's name",

            "email.required" => "Please add teacher's email",
            "email.email" => "Please add '@' to email",
            "email.unique" => "Please add unique email",

            "username.required" => "Please add teacher's username",
            "username.unique" => "Please add unique username",
        ];
    }


}
