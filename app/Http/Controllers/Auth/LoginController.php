<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = $request->input('identifier');
        $password = $request->input('password');

        // Try email first
        $user = User::where('email', $identifier)->first();

        // If not found, try NIC lookup via Teacher
        if (!$user) {
            $teacher = Teacher::where('nic', $identifier)->first();
            if ($teacher && $teacher->userId) {
                $user = User::find($teacher->userId);
            }
        }

        if (!$user || !$user->password) {
            return back()->withErrors(['identifier' => 'Invalid credentials.'])->withInput($request->only('identifier'));
        }

        if (!$user->isActive) {
            return back()->withErrors(['identifier' => 'Your account has been deactivated.'])->withInput($request->only('identifier'));
        }

        if (!Hash::check($password, $user->password)) {
            return back()->withErrors(['identifier' => 'Invalid credentials.'])->withInput($request->only('identifier'));
        }

        Auth::login($user, $request->filled('remember'));

        // Log audit
        \App\Models\AuditLog::create([
            'userId' => $user->id,
            'userEmail' => $user->email,
            'action' => 'LOGIN',
            'resourceType' => 'user',
            'resourceId' => $user->id,
        ]);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
