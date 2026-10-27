<?php

namespace App\Http\Controllers\Zonal;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $teachers = Teacher::with(['school', 'subjects.subject'])
            ->orderBy('nameWithInitials')
            ->get();

        $schools = School::where('isActive', true)->orderBy('name')->get(['id', 'name']);

        // Compute stats
        $totalTeachers = $teachers->count();
        $maleCount = $teachers->where('gender', 'Male')->count();
        $femaleCount = $teachers->where('gender', 'Female')->count();

        // Retiring within 5 years
        $currentYear = now()->year;
        $retiringCount = $teachers->filter(function ($t) use ($currentYear) {
            if (!$t->dateOfBirth) return false;
            $birthYear = $t->dateOfBirth->year;
            $yearsToRetirement = 60 - ($currentYear - $birthYear);
            return $yearsToRetirement > 0 && $yearsToRetirement <= 5;
        })->count();

        // SLPS (Principals) grade counts
        $slpsGrade3 = $teachers->where('serviceGrade', 'SLPS Grade III')->count();
        $slpsGrade2 = $teachers->where('serviceGrade', 'SLPS Grade II')->count();
        $slpsGrade1 = $teachers->where('serviceGrade', 'SLPS Grade I')->count();
        $totalPrincipals = $slpsGrade3 + $slpsGrade2 + $slpsGrade1;

        return view('dashboard.zonal.teachers.index', compact(
            'teachers', 'schools', 'totalTeachers', 'maleCount', 'femaleCount',
            'retiringCount', 'slpsGrade3', 'slpsGrade2', 'slpsGrade1', 'totalPrincipals'
        ));
    }

    public function create()
    {
        $schools = School::where('isActive', true)->orderBy('name')->get(['id', 'name']);
        $subjects = \App\Models\Subject::orderBy('name')->get(['id', 'name']);
        return view('dashboard.zonal.teachers.create', compact('schools', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nameWithInitials' => 'required|string|max:255',
            'fullName' => 'required|string|max:255',
            'nic' => 'required|string|max:20|unique:teacher,nic',
            'email' => 'nullable|email|max:255|unique:user,email',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'gender' => 'required|string',
            'dateOfBirth' => 'required|date',
            'address' => 'nullable|string',
            'maritalStatus' => 'nullable|string',
            'schoolId' => 'nullable|string',
            'fileNumber' => 'nullable|string|max:50',
            'salaryNumber' => 'nullable|string|max:50',
            'serviceGrade' => 'nullable|string',
            'currentPosition' => 'nullable|string',
            'appointmentType' => 'nullable|string',
            'appointmentDate' => 'nullable|date',
            'appointmentSubject' => 'nullable|string',
            'appointmentMedium' => 'nullable|string',
            'trainingStatus' => 'nullable|string',
            'natureOfAppointment' => 'nullable|string',
            'serviceGradeAppointedDate' => 'nullable|date',
            'joinedCurrentSchoolDate' => 'nullable|date',
            'subjects' => 'nullable|array',
        ]);

        // Remove subjects from validated data
        $subjectIds = $validated['subjects'] ?? [];
        unset($validated['subjects']);

        // Create User account
        $userEmail = $validated['email'] ?? ($validated['nic'] . '@wilgamuwa.com');
        $user = User::create([
            'name' => $validated['nameWithInitials'],
            'email' => $userEmail,
            'password' => Hash::make('password123'), // Default password
            'role' => User::ROLE_TEACHER,
            'schoolId' => $validated['schoolId'] ?? null,
            'isActive' => true,
        ]);

        // Create Teacher record
        $teacherData = $validated;
        $teacherData['id'] = (string) Str::ulid();
        $teacherData['userId'] = $user->id;
        $teacherData['updatedByUserId'] = auth()->id();
        
        // Ensure email matches user email if provided, or fallback
        if (!isset($teacherData['email'])) {
            $teacherData['email'] = $userEmail;
        }

        $teacher = Teacher::create($teacherData);

        // Sync subjects
        foreach ($subjectIds as $subjectId) {
            TeacherSubject::create([
                'id' => (string) Str::ulid(),
                'teacherId' => $teacher->id,
                'subjectId' => $subjectId,
            ]);
        }

        \App\Models\AuditLog::create([
            'userId' => auth()->id(),
            'userEmail' => auth()->user()->email,
            'action' => 'CREATE_TEACHER',
            'resourceType' => 'teacher',
            'resourceId' => $teacher->id,
            'details' => ['name' => $teacher->nameWithInitials],
        ]);

        return redirect()->route('zonal.teachers')->with('success', "Teacher '{$teacher->nameWithInitials}' created successfully.");
    }

    public function show(string $id)
    {
        $teacher = Teacher::with(['school', 'spouseSchool', 'subjects.subject', 'user'])
            ->findOrFail($id);
        $schools = School::where('isActive', true)->orderBy('name')->get(['id', 'name']);

        return view('dashboard.zonal.teachers.show', compact('teacher', 'schools'));
    }

    public function edit(string $id)
    {
        $teacher = Teacher::with(['school', 'spouseSchool', 'subjects.subject', 'user'])
            ->findOrFail($id);
        $schools = School::where('isActive', true)->orderBy('name')->get(['id', 'name']);
        $subjects = \App\Models\Subject::orderBy('name')->get(['id', 'name']);
        $teacherSubjectIds = $teacher->subjects->pluck('subjectId')->toArray();

        return view('dashboard.zonal.teachers.edit', compact('teacher', 'schools', 'subjects', 'teacherSubjectIds'));
    }

    public function update(Request $request, string $id)
    {
        $teacher = Teacher::findOrFail($id);

        $validated = $request->validate([
            'nameWithInitials' => 'required|string|max:255',
            'fullName' => 'required|string|max:255',
            'nic' => 'required|string|max:20',
            'schoolId' => 'nullable|string',
            'dateOfBirth' => 'nullable|date',
            'gender' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'maritalStatus' => 'nullable|string',
            'fileNumber' => 'nullable|string|max:50',
            'salaryNumber' => 'nullable|string|max:50',
            'serviceGrade' => 'nullable|string',
            'currentPosition' => 'nullable|string',
            'appointmentType' => 'nullable|string',
            'appointmentSubject' => 'nullable|string',
            'appointmentDate' => 'nullable|date',
            'appointmentMedium' => 'nullable|string',
            'trainingStatus' => 'nullable|string',
            'natureOfAppointment' => 'nullable|string',
            'serviceGradeAppointedDate' => 'nullable|date',
            'joinedCurrentSchoolDate' => 'nullable|date',
            'subjects' => 'nullable|array',
        ]);

        // Remove subjects from validated data (handled separately)
        $subjectIds = $validated['subjects'] ?? [];
        unset($validated['subjects']);

        $validated['updatedByUserId'] = auth()->id();

        $teacher->update($validated);

        // Sync User details if linked
        if ($teacher->userId) {
            $user = User::find($teacher->userId);
            if ($user) {
                $user->name = $teacher->nameWithInitials;
                if (isset($validated['email'])) {
                    $user->email = $validated['email'];
                }
                if (isset($validated['schoolId'])) {
                    $user->schoolId = $validated['schoolId'];
                }
                $user->save();
            }
        }

        // Sync subjects
        TeacherSubject::where('teacherId', $id)->delete();
        foreach ($subjectIds as $subjectId) {
            TeacherSubject::create([
                'id' => (string) Str::ulid(),
                'teacherId' => $id,
                'subjectId' => $subjectId,
            ]);
        }

        \App\Models\AuditLog::create([
            'userId' => auth()->id(),
            'userEmail' => auth()->user()->email,
            'action' => 'UPDATE_TEACHER',
            'resourceType' => 'teacher',
            'resourceId' => $id,
            'details' => ['name' => $teacher->nameWithInitials],
        ]);

        return redirect()->route('zonal.teachers')->with('success', "Teacher '{$teacher->nameWithInitials}' updated successfully.");
    }


    public function destroy(string $id)
    {
        $teacher = Teacher::findOrFail($id);
        $name = $teacher->nameWithInitials;

        // Remove linked user account if exists
        if ($teacher->userId) {
            User::where('id', $teacher->userId)->delete();
        }

        $teacher->delete();

        \App\Models\AuditLog::create([
            'userId' => auth()->id(),
            'userEmail' => auth()->user()->email,
            'action' => 'DELETE_TEACHER',
            'resourceType' => 'teacher',
            'resourceId' => $id,
            'details' => ['name' => $name],
        ]);

        return back()->with('success', "Teacher '{$name}' removed.");
    }

    public function removeForm()
    {
        return view('dashboard.zonal.teachers.remove');
    }

    public function removeTeacher(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teacher,id',
            'reason' => 'required|string',
            'effective_date' => 'required|date',
        ]);

        $teacher = Teacher::findOrFail($request->teacher_id);
        $name = $teacher->nameWithInitials;

        // Remove linked user account if exists
        if ($teacher->userId) {
            User::where('id', $teacher->userId)->delete();
        }

        $teacher->delete();

        \App\Models\AuditLog::create([
            'userId' => auth()->id(),
            'userEmail' => auth()->user()->email,
            'action' => 'DELETE_TEACHER',
            'resourceType' => 'teacher',
            'resourceId' => $request->teacher_id,
            'details' => [
                'name' => $name,
                'reason' => $request->reason,
                'effective_date' => $request->effective_date,
            ],
        ]);

        return redirect()->route('zonal.teachers')->with('success', "Teacher '{$name}' permanently removed.");
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $teachers = Teacher::with('school')->where('nic', 'like', "%{$query}%")
            ->orWhere('nameWithInitials', 'like', "%{$query}%")
            ->orWhere('fullName', 'like', "%{$query}%")
            ->limit(8)
            ->get();

        return response()->json($teachers);
    }
}
