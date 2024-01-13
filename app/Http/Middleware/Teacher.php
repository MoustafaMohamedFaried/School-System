<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Teacher
{
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check() && auth()->user()->role_id == 2) //? access if user is teacher
            return $next($request);
        else
            abort(403); //* forbidden page
    }
}
