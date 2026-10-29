<?php

namespace App\Http\Controllers\Zonal;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use App\Services\TeacherOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $schools = School::where('isActive', true)->orderBy('name')->get();
        $options = TeacherOptions::all();
        return view('dashboard.zonal.teachers.create', compact('schools', 'options'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nic' => 'required|string|max:20|unique:teacher,nic',
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'census_no' => 'nullable|string',
            'schoolId' => 'nullable|string',
            'email' => 'nullable|email|max:255|unique:user,email',
            'mobile' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:50',
        ]);

        $nic = strtoupper(trim($request->input('nic')));
        $fullName = trim($request->input('full_name'));
        $nameWithInitials = $request->input('nameWithInitials') ?: $fullName;
        $censusNo = $request->input('census_no');
        $schoolId = $request->input('schoolId');

        if (!$schoolId && $censusNo) {
            $sc = School::where('censusNo', $censusNo)->first();
            if ($sc) {
                $schoolId = $sc->id;
            }
        }

        // Format profall
        $profall = $request->input('profall');
        if (is_array($profall)) {
            $profall = implode('|', $profall);
        }

        // Format otherschool_days
        $otherDays = $request->input('otherschool_days');
        if (is_array($otherDays)) {
            $otherDays = implode('|', $otherDays);
        }

        $allInputs = $request->all();
        $allInputs['nic'] = $nic;

        // Create User account
        $userEmail = $request->input('email') ?: ($nic . '@wilgamuwa.com');
        $user = User::create([
            'name' => $nameWithInitials,
            'email' => $userEmail,
            'password' => Hash::make('password123'),
            'role' => User::ROLE_TEACHER,
            'schoolId' => $schoolId,
            'isActive' => true,
        ]);

        // Create Teacher record
        $teacherData = [
            'id' => (string) Str::ulid(),
            'userId' => $user->id,
            'schoolId' => $schoolId,
            'census_no' => $censusNo,
            'nic' => $nic,
            'paysheet' => $request->input('paysheet'),
            'title' => $request->input('title'),
            'personal_file_no' => $request->input('personal_file_no'),
            'nameWithInitials' => $nameWithInitials,
            'fullName' => $fullName,
            'dateOfBirth' => $request->input('date_of_birth'),
            'gender' => $request->input('gender'),
            'ethnicity' => $request->input('ethnicity'),
            'ethnicity_other' => $request->input('ethnicity_other'),
            'religion' => $request->input('religion'),
            'religion_other' => $request->input('religion_other'),
            'address' => $request->input('address'),
            'phone' => $request->input('mobile') ?: $request->input('phone'),
            'whatsapp' => $request->input('whatsapp'),
            'email' => $userEmail,
            'maritalStatus' => $request->input('marital'),
            'marital_other' => $request->input('marital_other'),
            'eduqua' => $request->input('eduqua'),
            'subject_stream' => $request->input('subject') ?: $request->input('subject_stream'),
            'field_study' => $request->input('field') ?: $request->input('field_study'),
            'field_study_other' => $request->input('field_other') ?: $request->input('field_study_other'),
            'profqua' => $request->input('profqua'),
            'profqua_other' => $request->input('profqua_other'),
            'profall' => $profall,
            'service' => $request->input('service'),
            'firstappoint' => $request->input('firstappoint'),
            'class_grade' => $request->input('class') ?: $request->input('class_grade'),
            'clsappoint' => $request->input('clsappoint'),
            'schappoint' => $request->input('schappoint'),
            'appt_section' => $request->input('appt_section'),
            'appt_category' => $request->input('appt_category'),
            'appt_code' => $request->input('appt_code'),
            'appt_medium' => $request->input('appt_medium'),
            'mainduty' => $request->input('mainduty'),
            'mainduty_other' => $request->input('mainduty_other'),
            'secondduty' => $request->input('secondduty'),
            'secondduty_other' => $request->input('secondduty_other'),
            't1_section' => $request->input('t1_section'),
            't1_category' => $request->input('t1_category'),
            't1_code' => $request->input('t1_code'),
            't1_medium' => $request->input('t1_medium'),
            't1_periods' => $request->input('t1_periods') ? (int)$request->input('t1_periods') : null,
            't2_section' => $request->input('t2_section'),
            't2_category' => $request->input('t2_category'),
            't2_code' => $request->input('t2_code'),
            't2_medium' => $request->input('t2_medium'),
            't2_periods' => $request->input('t2_periods') ? (int)$request->input('t2_periods') : null,
            'able1_section' => $request->input('able1_section'),
            'able1_category' => $request->input('able1_category'),
            'able1_code' => $request->input('able1_code'),
            'able1_medium' => $request->input('able1_medium'),
            'able2_section' => $request->input('able2_section'),
            'able2_category' => $request->input('able2_category'),
            'able2_code' => $request->input('able2_code'),
            'able2_medium' => $request->input('able2_medium'),
            'otherschool' => $request->input('otherschool'),
            'otherschool_census' => $request->input('otherschool_census'),
            'otherschool_days' => $otherDays,
            'position' => $request->input('position'),
            'salaryby' => $request->input('salaryby'),
            'servicebased' => $request->input('servicebased'),
            'salarysource' => $request->input('salarysource'),
            'appointmentDate' => $request->input('firstappoint'),
            'appointmentType' => $request->input('service'),
            'appointmentMedium' => $request->input('appt_medium'),
            'serviceGrade' => $request->input('class') ?: $request->input('class_grade'),
            'serviceGradeAppointedDate' => $request->input('clsappoint'),
            'currentPosition' => $request->input('position'),
            'joinedCurrentSchoolDate' => $request->input('schappoint'),
            'fileNumber' => $request->input('personal_file_no'),
            'salaryNumber' => $request->input('paysheet'),
            'form_data' => $allInputs,
            'createdByUserId' => auth()->id(),
            'updatedByUserId' => auth()->id(),
        ];

        $teacher = Teacher::create($teacherData);

        // Also record submission in teacher_submissions
        DB::table('teacher_submissions')->insert([
            'id' => (string) Str::ulid(),
            'created_at' => now(),
            'census_no' => $censusNo ?: '',
            'school_name' => $teacher->school ? $teacher->school->name : ($censusNo ?: ''),
            'nic' => $nic,
            'full_name' => $fullName,
            'mobile' => $request->input('mobile') ?: $request->input('phone'),
            'data' => json_encode($allInputs),
        ]);

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
        $teacher = Teacher::with(['school', 'otherSchool', 'spouseSchool', 'subjects.subject', 'user'])
            ->findOrFail($id);
        $schools = School::where('isActive', true)->orderBy('name')->get();
        $options = TeacherOptions::all();

        return view('dashboard.zonal.teachers.show', compact('teacher', 'schools', 'options'));
    }

    public function edit(string $id)
    {
        $teacher = Teacher::with(['school', 'otherSchool', 'spouseSchool', 'subjects.subject', 'user'])
            ->findOrFail($id);
        $schools = School::where('isActive', true)->orderBy('name')->get();
        $options = TeacherOptions::all();

        return view('dashboard.zonal.teachers.edit', compact('teacher', 'schools', 'options'));
    }

    public function update(Request $request, string $id)
    {
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'nic' => 'required|string|max:20|unique:teacher,nic,' . $id,
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'email' => 'nullable|email|max:255',
        ]);

        $nic = strtoupper(trim($request->input('nic')));
        $fullName = trim($request->input('full_name'));
        $nameWithInitials = $request->input('nameWithInitials') ?: $fullName;
        $censusNo = $request->input('census_no');
        $schoolId = $request->input('schoolId');

        if (!$schoolId && $censusNo) {
            $sc = School::where('censusNo', $censusNo)->first();
            if ($sc) {
                $schoolId = $sc->id;
            }
        }

        // Format profall
        $profall = $request->input('profall');
        if (is_array($profall)) {
            $profall = implode('|', $profall);
        }

        // Format otherschool_days
        $otherDays = $request->input('otherschool_days');
        if (is_array($otherDays)) {
            $otherDays = implode('|', $otherDays);
        }

        $allInputs = $request->all();
        $allInputs['nic'] = $nic;

        $updateData = [
            'schoolId' => $schoolId ?: $teacher->schoolId,
            'census_no' => $censusNo ?: $teacher->census_no,
            'nic' => $nic,
            'paysheet' => $request->input('paysheet'),
            'title' => $request->input('title'),
            'personal_file_no' => $request->input('personal_file_no'),
            'nameWithInitials' => $nameWithInitials,
            'fullName' => $fullName,
            'dateOfBirth' => $request->input('date_of_birth') ?: $teacher->dateOfBirth,
            'gender' => $request->input('gender'),
            'ethnicity' => $request->input('ethnicity'),
            'ethnicity_other' => $request->input('ethnicity_other'),
            'religion' => $request->input('religion'),
            'religion_other' => $request->input('religion_other'),
            'address' => $request->input('address'),
            'phone' => $request->input('mobile') ?: $request->input('phone'),
            'whatsapp' => $request->input('whatsapp'),
            'email' => $request->input('email') ?: $teacher->email,
            'maritalStatus' => $request->input('marital'),
            'marital_other' => $request->input('marital_other'),
            'eduqua' => $request->input('eduqua'),
            'subject_stream' => $request->input('subject') ?: $request->input('subject_stream'),
            'field_study' => $request->input('field') ?: $request->input('field_study'),
            'field_study_other' => $request->input('field_other') ?: $request->input('field_study_other'),
            'profqua' => $request->input('profqua'),
            'profqua_other' => $request->input('profqua_other'),
            'profall' => $profall,
            'service' => $request->input('service'),
            'firstappoint' => $request->input('firstappoint'),
            'class_grade' => $request->input('class') ?: $request->input('class_grade'),
            'clsappoint' => $request->input('clsappoint'),
            'schappoint' => $request->input('schappoint'),
            'appt_section' => $request->input('appt_section'),
            'appt_category' => $request->input('appt_category'),
            'appt_code' => $request->input('appt_code'),
            'appt_medium' => $request->input('appt_medium'),
            'mainduty' => $request->input('mainduty'),
            'mainduty_other' => $request->input('mainduty_other'),
            'secondduty' => $request->input('secondduty'),
            'secondduty_other' => $request->input('secondduty_other'),
            't1_section' => $request->input('t1_section'),
            't1_category' => $request->input('t1_category'),
            't1_code' => $request->input('t1_code'),
            't1_medium' => $request->input('t1_medium'),
            't1_periods' => $request->input('t1_periods') ? (int)$request->input('t1_periods') : null,
            't2_section' => $request->input('t2_section'),
            't2_category' => $request->input('t2_category'),
            't2_code' => $request->input('t2_code'),
            't2_medium' => $request->input('t2_medium'),
            't2_periods' => $request->input('t2_periods') ? (int)$request->input('t2_periods') : null,
            'able1_section' => $request->input('able1_section'),
            'able1_category' => $request->input('able1_category'),
            'able1_code' => $request->input('able1_code'),
            'able1_medium' => $request->input('able1_medium'),
            'able2_section' => $request->input('able2_section'),
            'able2_category' => $request->input('able2_category'),
            'able2_code' => $request->input('able2_code'),
            'able2_medium' => $request->input('able2_medium'),
            'otherschool' => $request->input('otherschool'),
            'otherschool_census' => $request->input('otherschool_census'),
            'otherschool_days' => $otherDays,
            'position' => $request->input('position'),
            'salaryby' => $request->input('salaryby'),
            'servicebased' => $request->input('servicebased'),
            'salarysource' => $request->input('salarysource'),
            'appointmentDate' => $request->input('firstappoint') ?: $teacher->appointmentDate,
            'appointmentType' => $request->input('service') ?: $teacher->appointmentType,
            'appointmentMedium' => $request->input('appt_medium') ?: $teacher->appointmentMedium,
            'serviceGrade' => ($request->input('class') ?: $request->input('class_grade')) ?: $teacher->serviceGrade,
            'serviceGradeAppointedDate' => $request->input('clsappoint') ?: $teacher->serviceGradeAppointedDate,
            'currentPosition' => $request->input('position') ?: $teacher->currentPosition,
            'joinedCurrentSchoolDate' => $request->input('schappoint') ?: $teacher->joinedCurrentSchoolDate,
            'fileNumber' => $request->input('personal_file_no') ?: $teacher->fileNumber,
            'salaryNumber' => $request->input('paysheet') ?: $teacher->salaryNumber,
            'form_data' => array_merge((array)$teacher->form_data, $allInputs),
            'updatedByUserId' => auth()->id(),
        ];

        $teacher->update($updateData);

        // Sync User details if linked
        if ($teacher->userId) {
            $user = User::find($teacher->userId);
            if ($user) {
                $user->name = $teacher->nameWithInitials;
                if (!empty($updateData['email'])) {
                    $user->email = $updateData['email'];
                }
                if (!empty($updateData['schoolId'])) {
                    $user->schoolId = $updateData['schoolId'];
                }
                $user->save();
            }
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
