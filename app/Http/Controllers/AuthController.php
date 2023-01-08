<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {

        $field = filter_var($request->input('login_field'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $request->input('login_field')]);

        $credentials = $request->validate([
            'email' => 'required_without:username|string|email|exists:users,email',
            'username' => 'required_without:email|string|exists:users,username',
            'password' => 'required|string'
        ]);

        if (Auth::viaRemember()) {
            $request->session()->regenerate();

            return auth()->user();
        }

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            return auth()->user();
        }
        return response()->json([
            'message' => 'Incorrect username/email or password',
            'type' => 'error'
        ], 403);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }

    public function me(Request $request)
    {
        return response()->json([
            'data' => $request->user()
        ]);
    }
}
