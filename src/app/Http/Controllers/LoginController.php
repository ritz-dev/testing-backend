<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login() {
        // if(Auth::user()) {
        //     return redirect()->to('/dashboard');
        // }
        return view('signin');
    }
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        // Validate the login request
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Determine if the login is an email or phone number
        $loginType = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Attempt to log in the user
        $credentials = [
            $loginType => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $user=Auth::user();
            if ($user->role_id == 1 || $user->role_id == 2 ) {
                 return redirect()->route('dashboard');
             } elseif ($user->role_id == 0) {
                 return redirect()->route('booking.index');
             } else {
                 return redirect('/');
             }
        }
        return back()->withErrors(['login' => 'Invalid login credentials']);
    }
    /**
    * Log the user out of the application.
    */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
