<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $school = School::find(auth()->user()->schoolId);
        if (!$school) abort(404, 'No school assigned.');

        $query = Teacher::with(['subjects.subject'])
            ->where('schoolId', $school->id);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nameWithInitials', 'like', "%{$search}%")
                  ->orWhere('nic', 'like', "%{$search}%");
            });
        }

        $teachers = $query->orderBy('nameWithInitials')->paginate(20);
        return view('dashboard.school.teachers.index', compact('school', 'teachers'));
    }

    public function create()
    {
        $school = School::find(auth()->user()->schoolId);
        $subjects = Subject::orderBy('name')->get();
        return view('dashboard.school.teachers.create', compact('school', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nic' => 'required|string|max:191|unique:teacher,nic',
            'nameWithInitials' => 'required|string|max:191',
            'fullName' => 'required|string|max:191',
            'gender' => 'nullable|string',
            'dateOfBirth' => 'nullable|date',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'appointmentType' => 'nullable|string',
            'appointmentDate' => 'nullable|date',
            'serviceGrade' => 'nullable|string',
            'currentPosition' => 'nullable|string',
        ]);

        $validated['schoolId'] = auth()->user()->schoolId;
        $validated['createdByUserId'] = auth()->id();

        // Create teacher user account
        $user = User::create([
            'name' => $validated['nameWithInitials'],
            'email' => $validated['email'] ?? $validated['nic'] . '@zems.local',
            'password' => Hash::make($validated['nic']),
            'role' => User::ROLE_TEACHER,
            'schoolId' => $validated['schoolId'],
        ]);

        $validated['userId'] = $user->id;

        $teacher = Teacher::create($validated);

        \App\Models\AuditLog::create([
            'userId' => auth()->id(),
            'userEmail' => auth()->user()->email,
            'action' => 'CREATE_TEACHER',
            'resourceType' => 'teacher',
            'resourceId' => $teacher->id,
        ]);

        return redirect()->route('school.teachers')->with('success', 'Teacher added successfully!');
    }
}
