<?php

namespace App\Http\Controllers\Zonal;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('school');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('createdAt', 'desc')->paginate(20);
        $schools = School::where('isActive', true)->orderBy('name')->get(['id', 'name']);

        return view('dashboard.zonal.users.index', compact('users', 'schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:user,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:ZONAL_ADMIN,SCHOOL_ADMIN,TEACHER',
            'schoolId' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'schoolId' => $validated['schoolId'] ?? null,
        ]);

        \App\Models\AuditLog::create([
            'userId' => auth()->id(),
            'userEmail' => auth()->user()->email,
            'action' => 'CREATE_USER',
            'resourceType' => 'user',
            'resourceId' => $user->id,
            'details' => ['email' => $user->email, 'role' => $user->role],
        ]);

        return back()->with('success', 'User created successfully!');
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:user,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:ZONAL_ADMIN,SCHOOL_ADMIN,TEACHER',
            'schoolId' => 'nullable|string',
            'isActive' => 'boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'schoolId' => $validated['schoolId'] ?? null,
            'isActive' => $request->has('isActive') ? $validated['isActive'] : false,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        \App\Models\AuditLog::create([
            'userId' => auth()->id(),
            'userEmail' => auth()->user()->email,
            'action' => 'UPDATE_USER',
            'resourceType' => 'user',
            'resourceId' => $user->id,
            'details' => ['email' => $user->email, 'role' => $user->role, 'isActive' => $user->isActive],
        ]);

        return back()->with('success', 'User updated successfully!');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Cannot delete your own account.']);
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
