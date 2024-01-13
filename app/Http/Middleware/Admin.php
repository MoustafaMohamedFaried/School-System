<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check() && auth()->user()->role_id == 1) //? access if user is admin
            return $next($request);
        else
            abort(401); //* unauthorize page
    }
}
