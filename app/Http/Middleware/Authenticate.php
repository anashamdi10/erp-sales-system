<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
        /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if ($request->is('admin') || $request->is('admin/*')) {
                //redirect to admin login
                return route('admin.showlogin');
            } else {
                //redirect to fron login in case there is front 
                //return route('login');
                return route('admin.showlogin');
            }
        }
    }
}
