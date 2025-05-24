<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            session([
                'user' => $user,
                'access' => $user->role->access
            ]);

            if ($user->role_id == 1) {
                return redirect()->intended('/home');
            } elseif ($user->role_id >= 2 && $user->role_id <= 6) {
                return redirect()->intended('/dashboard');
            } else {
                Auth::logout();
                return redirect('/login')->withErrors(['role' => 'Akses ditolak!']);
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah!']);
    }

    public function logout()
    {
        Auth::logout();
        session()->forget('user');
        session()->forget('access');
        return redirect('/login');
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 1,
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silahkan login.');
    }
}
