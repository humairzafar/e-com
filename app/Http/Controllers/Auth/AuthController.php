<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginAction(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required',
            'remember_me' => 'sometimes',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            if ($user->is_active == 0) {
                Auth::logout();
                return back()->withErrors('Your account is not active, please contact the admin');
            }

            return redirect()->route('home');
        }

        return back()->withErrors('credentials not match');
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
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
            'phone_number' => 'required|string',
            // 'joining_date' => 'sometimes|date',
            // 'bio' => 'sometimes|string',
        ]);

        $user = Auth::user();

        // $user->update($request->all());

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        // $user->joining_date = $request->joining_date;
        // $user->bio = $request->bio;
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
            'email' => 'required|email|unique:users,email,',
            'password' => ['required', 'min:8', 'confirmed'],
            ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        SendEmailJob::dispatch($request->email);
        return redirect()->route('login')->with('success', 'Please check your email for activation');
    }
}
