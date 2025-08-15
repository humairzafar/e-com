<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginAction(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',
            'remember_me' => 'sometimes',
        ]);

        // Check user exists and is active
        $user = User::where('email', $request->email)->first();
        if ($user && !$user->is_active) {
            return back()->withErrors('Your account is not active. Please verify your email.');
        }

        // Attempt login only if active
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'is_active' => 1
        ], $request->remember_me)) {
            return redirect()->route('home');
        }

        return back()->withErrors('Invalid credentials or account not active.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'phone_number' => 'required|string',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['success' => true, 'message' => 'Password updated successfully']);
    }

    public function register()
    {
        return view('auth.register');
    }

    public function registerAction(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // Create inactive user with verification token
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->verification_token = Str::random(64);
        $user->is_active = 0;
        $user->save();

        // Create verification URL
        $activationLink = route('verify.email', [
            'token' => $user->verification_token,
            'email' => $user->email
        ]);

        // Send verification email
        Mail::send('emails.verify', [
    'activationLink' => $activationLink,
    'token' => $user->verification_token,
    'email' => $user->email,
    'user' => $user,
], function ($message) use ($user) {
    $message->to($user->email)
            ->subject('Verify Your Email');
});

        return redirect()->route('login')->with('success', 'Please check your email to activate your account.');
    }

    public function verifyemail($id)
    {
        $user = User::find($id);

    if ($user) {
        $user->is_active = 1;
        $user->save();
        return redirect('/login')->with('success', 'Your email has been verified. You can now log in.');
    }

    return redirect('/login')->with('error', 'Invalid verification link.');
}
}
