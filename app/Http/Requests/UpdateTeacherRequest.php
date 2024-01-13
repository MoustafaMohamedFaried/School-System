<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::user()->id; //? Get the authenticated user's ID

        return [
            "name" => "required|string",
            "email" => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId), //? Ignore the current user's email
            ],
            "username" => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($userId), //? Ignore the current user's username
            ],
            "password" => "nullable|min:8"
        ];
    }
}
