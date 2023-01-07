<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {

        $field = filter_var($request->input('login_field'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $this->merge([$field => $request->input('login_field')]);

        $credentials = $request->validate([
            'email' => 'required_without:username|string|email|exists:users,email',
            'username' => 'required_without:email|string|exists:users,username',
        ]);

        if(Auth::attempt($credentials))
        {
            $request->session()->regenerate();

            return auth()->user();
        }
         return response()->json([
            'message' => 'Incorrect email or password',
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
