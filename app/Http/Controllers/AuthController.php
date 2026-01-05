<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];

        if (setting('recaptcha.enabled')) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $credentials = $request->validate($rules);

        // Remove captcha from credentials before attempt
        unset($credentials['g-recaptcha-response']);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Ban check
            if (Auth::user()->isBanned()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been banned: ' . Auth::user()->banned_reason,
                ]);
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if (setting('recaptcha.enabled')) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $request->validate($rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign default user role
        $user->assignRole('user');

        Auth::login($user);

        return redirect(route('home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
