<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SetDatabaseConnection
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('db_connection')) {
            Config::set('database.default', session('db_connection'));
        }

        return $next($request);
    }
} 