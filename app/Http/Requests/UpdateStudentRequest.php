<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => "required|string",
            "age" => "required|numeric",
            "teacher_id" => "nullable",
        ];
    }

    public function messages(): array
    {
        return [
            "age.required" => "Please add student's age",
            "age.numeric" => "Student's age must be numeric",

            "name.required" => "Please add student's name",

        ];
    }
}
