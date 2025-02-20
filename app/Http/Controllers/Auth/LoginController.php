<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $units = Unit::all();
        return view('auth.login', compact('units'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'unit' => ['required', 'exists:units,id']
        ]);

        // Get unit and corresponding database connection
        $unit = Unit::find($request->unit);
        $connection = $this->getConnectionForUnit($unit);

        // Set the database connection
        Config::set('database.default', $connection);
        
        // Penting: Set koneksi untuk Auth facade
        Auth::setProvider(Auth::createUserProvider($connection));
        
        // Debug info
        Log::info('Attempting login with connection: ' . $connection);
        Log::info('Current default connection: ' . Config::get('database.default'));
        
        // Attempt to authenticate against the specific database
        try {
            if (Auth::attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password']
            ])) {
                $request->session()->regenerate();
                
                // Store unit and connection info in session
                session([
                    'unit_id' => $unit->id,
                    'unit_name' => $unit->name,
                    'db_connection' => $connection
                ]);

                Log::info('Login successful for user: ' . $credentials['email'] . ' on connection: ' . $connection);
                return redirect()->intended('dashboard');
            }
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'Authentication error occurred.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    private function getConnectionForUnit($unit)
    {
        // Map unit names to database connections
        $connectionMap = [
            'PLTD Bau Bau' => 'mysql_bau_bau',
            'PLTD Kolaka' => 'mysql_kolaka',
            'PLTD Poasia' => 'mysql_poasia',
            'PLTD Wua Wua' => 'mysql_wua_wua',
            'PLTD Raha' => 'mysql_raha',
            'PLTD WANGI-WANGI' => 'mysql_wangi',
            'PLTD LANGARA' => 'mysql_langara',
            'PLTD EREKE' => 'mysql_ereke',
            'PLTU BARUTA' => 'mysql_pltu_bau',
            'PLTM RONGI' => 'mysql_rongi',
            'PLTM Winning' => 'mysql_winning',
        ];

        // Get connection name from map or return default
        $connection = $connectionMap[$unit->name] ?? 'mysql';
        
        // Debug info
        Log::info('Selected Unit: ' . $unit->name);
        Log::info('Using Connection: ' . $connection);
        
        return $connection;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
