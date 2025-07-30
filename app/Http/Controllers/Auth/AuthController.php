<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
            Auth::user();

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
            'joining_date' => 'sometimes|date',
            'bio' => 'sometimes|string',
        ]);

        $user = Auth::user();

        // $user->update($request->all());

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->joining_date = $request->joining_date;
        $user->bio = $request->bio;
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
}
