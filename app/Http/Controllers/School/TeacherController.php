<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use App\Models\TeacherSubject;
use App\Services\TeacherOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                  ->orWhere('fullName', 'like', "%{$search}%")
                  ->orWhere('nic', 'like', "%{$search}%");
            });
        }

        $teachers = $query->orderBy('nameWithInitials')->paginate(20);
        return view('dashboard.school.teachers.index', compact('school', 'teachers'));
    }

    public function create()
    {
        $school = School::find(auth()->user()->schoolId);
        $schools = School::where('isActive', true)->orderBy('name')->get();
        $options = TeacherOptions::all();
        $subjects = Subject::orderBy('name')->get();
        return view('dashboard.school.teachers.create', compact('school', 'schools', 'options', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nic' => 'required|string|max:20|unique:teacher,nic',
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'email' => 'nullable|email|max:255',
        ]);

        $nic = strtoupper(trim($request->input('nic')));
        $fullName = trim($request->input('full_name'));
        $nameWithInitials = $request->input('nameWithInitials') ?: $fullName;
        $schoolId = auth()->user()->schoolId;
        $school = School::find($schoolId);
        $censusNo = $school ? $school->censusNo : $request->input('census_no');

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

        // Create teacher user account
        $userEmail = $request->input('email') ?: ($nic . '@wilgamuwa.com');
        $user = User::create([
            'name' => $nameWithInitials,
            'email' => $userEmail,
            'password' => Hash::make('password123'),
            'role' => User::ROLE_TEACHER,
            'schoolId' => $schoolId,
            'isActive' => true,
        ]);

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
            'school_name' => $school ? $school->name : ($censusNo ?: ''),
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

        return redirect()->route('school.teachers')->with('success', "Teacher '{$teacher->nameWithInitials}' added successfully!");
    }
}
