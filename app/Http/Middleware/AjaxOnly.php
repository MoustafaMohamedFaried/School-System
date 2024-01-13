<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->ajax())
        {
            abort(401); //* unauthorize page
        }
        return $next($request);
    }
}
