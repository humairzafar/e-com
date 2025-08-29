<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
// use Spatie\Permission\Contracts\Permission;
// use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthController extends Controller
{
    public function  index()
    {
        $users = user::all();
        $roles = Role::orderBy('name')->get();              // for role dropdowns
        $permissions = Permission::orderBy('name')->get();
        return view('e-com.users.index' , compact('users','roles','permissions'));

    }
      public function store(Request $request)
    {

        $validated=$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|',
            'is_active' => 'required|boolean',
            'role_id' => 'required|exists:roles,id',
        ]);

        // ✅ Step 2: Create user
        $user = User::create($validated);

        // ✅ Step 3: Assign role
         $role = Role::find($request->role_id);
        if ($role) {
        $user->assignRole($role);
        } else {
        return back()->withErrors('Selected role does not exist.');
        }

        // ✅ Step 4: Assign permissions (if any)
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return $this->getLatestRecords('User Add successfully');
    }
    public function edit($id)
    {
        $user = User::with('role', 'permissions')->findOrFail($id);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_active' => $user->is_active,
            'role_id' => $user->role_id,
            'permissions' => $user->permissions->pluck('id')->toArray()
        ]);
        $request->validate([
            'id' => 'required|exists:users,id'
        ]);

        $user = User::with('role', 'permissions')->findOrFail($request->id);

        return response()->json($user);
    }
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
            'is_active' => 'required|boolean',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($request->id);
        $user->update($validated);

        // Update role
        $user->syncRoles([$request->role]);

        // Update permissions
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        } else {
            $user->syncPermissions([]); // Remove all permissions if none selected
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully with role and permissions.');
    }
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
private function getLatestRecords($message = 'Roles fetched successfully')
    {
       $users = User::latest('created_at')->get();
        return response()->json([
            'success' => true,
            'html' => view('e-com.users.data-table', compact('users'))->render(),
            'message' => $message,
        ]);
    }
}
