<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SetDatabaseConnection
{
    public function handle(Request $request, Closure $next)
    {
        // Ambil koneksi dari session yang di-set saat login
        $connection = session('db_connection');

        if ($connection) {
            // Set koneksi default untuk request ini
            Config::set('database.default', $connection);
        }

        return $next($request);
    }
} 