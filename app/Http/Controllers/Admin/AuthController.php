<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }
    public function login(AdminLoginRequest $request)
    {
        $validated = $request->validated();

        if (
            !Auth::attempt([
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'admin',
            ])
        ) {
            return back()->withErrors([
                'email' => 'Invalid admin credentials',
            ])->withInput();
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }
}
