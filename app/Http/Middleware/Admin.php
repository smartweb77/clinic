<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! \App\Models\Admin::isLogin()) {
            return redirect()->route('LoginPageAdmin');
        }

        return $next($request);
    }
}
