<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // ==========================================================
    // 🔹 LOGIN
    // ==========================================================
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($loginType, $request->login)->first();

        if (!$user) {
            return back()->withErrors(['login' => 'Username atau email tidak ditemukan.']);
        }

        // 🔹 Jika password di database masih plaintext, hash otomatis
        if (!Hash::check($request->password, $user->password)) {
            if ($user->password === $request->password) {
                $user->password = Hash::make($request->password);
                $user->save();
            } else {
                return back()->withErrors(['login' => 'Username atau password salah.']);
            }
        }

        // 🔹 Login berhasil
        Auth::login($user);
        $request->session()->regenerate();

        // Simpan info tambahan di session (pakai user_id agar sesuai model)
        session([
            'user_id'   => $user->user_id,
            'role'      => $user->role,
            'username'  => $user->username,
            'full_name' => $user->full_name ?? $user->username,
        ]);

        // 🔹 Arahkan ke dashboard sesuai role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('dashboard')->with('success', 'Selamat datang kembali, Admin!');
            case 'teamlead':
            case 'team_lead':
                return redirect()->route('teamlead.dashboard')->with('success', 'Selamat datang kembali, Team Lead!');
            case 'developer':
                return redirect()->route('developer.dashboard')->with('success', 'Halo Developer!');
            case 'designer':
                return redirect()->route('designer.dashboard')->with('success', 'Halo Designer!');
            default:
                return redirect()->route('dashboard')->with('success', 'Selamat datang kembali!');
        }
    }

    // ==========================================================
    // 🔹 REGISTER
    // ==========================================================
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'nullable|string'
        ]);

        // Default role = developer jika tidak diisi
        $role = $request->role ?: 'developer';

        $user = User::create([
            'username'   => $request->username,
            'full_name'  => $request->username,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => strtolower($role),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat! Selamat datang, ' . $user->username . '.');
    }

    // ==========================================================
    // 🔹 LOGOUT
    // ==========================================================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout!');
    }
}
