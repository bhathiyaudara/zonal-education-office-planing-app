<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Teacher;
use App\Models\User;
use App\Services\TeacherOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherFormController extends Controller
{
    public function show()
    {
        $options = TeacherOptions::all();
        $schools = School::where('isActive', true)->orderBy('name')->get();
        return view('teacher-form', compact('options', 'schools'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'census_no' => 'required|string',
            'nic' => ['required', 'string', 'regex:/^(\d{9}[VvXx]|\d{12})$/'],
            'full_name' => 'required|string|min:2|max:200',
            'date_of_birth' => 'required|date',
            'mobile' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'email' => 'nullable|email',
            'certify' => 'required|in:yes,1,on',
        ], [
            'census_no.required' => 'Please select a school.',
            'nic.required' => 'National Identity Card No is required.',
            'nic.regex' => 'Enter a valid NIC (e.g. 948232790V or 199570503360).',
            'full_name.required' => 'Name as on Paysheet is required.',
            'date_of_birth.required' => 'Date of birth is required.',
            'certify.required' => 'Please certify that the information provided is true and accurate.',
        ]);

        $nic = strtoupper(trim($request->input('nic')));
        $fullName = trim($request->input('full_name'));
        $censusNo = trim($request->input('census_no'));

        // Find school
        $school = School::where('censusNo', $censusNo)->first();
        $schoolName = $school ? $school->name : '';

        // If school not in DB yet, try to find in teacherOptions.json
        if (!$school) {
            $allSchools = TeacherOptions::schools();
            foreach ($allSchools as $s) {
                if ($s['censusNo'] === $censusNo) {
                    $schoolName = $s['name'];
                    // create school record
                    $school = School::create([
                        'id' => (string) Str::ulid(),
                        'name' => $s['name'],
                        'censusNo' => $s['censusNo'],
                        'category' => $s['info']['School Category'] ?? null,
                        'schoolType' => $s['info']['Approved School Type'] ?? null,
                        'eduZone' => $s['info']['Edu Zone_2026'] ?? 'Wilgamuwa',
                        'eduDivision' => $s['info']['Edu Division_2026'] ?? 'Wilgamuwa',
                        'sanctionedPositions' => isset($s['info']['2025 Staff Base']) ? (int)$s['info']['2025 Staff Base'] : null,
                        'isActive' => true,
                    ]);
                    break;
                }
            }
        }

        // Other school/office/institute lookup
        $otherSchoolCensus = $request->input('otherschool_census');
        $otherSchoolName = $otherSchoolCensus ? TeacherOptions::institutionName($otherSchoolCensus) : '';

        // Prepare profall
        $profall = $request->input('profall');
        if (is_array($profall)) {
            $profall = implode('|', $profall);
        }

        // Prepare otherschool_days
        $otherDays = $request->input('otherschool_days');
        if (is_array($otherDays)) {
            $otherDays = implode('|', $otherDays);
        }

        $allInputs = $request->all();
        $allInputs['nic'] = $nic;
        $allInputs['school_name'] = $schoolName;
        $allInputs['otherschool_name'] = $otherSchoolName;

        // 1. Save or update teacher_submissions
        $submissionId = (string) Str::ulid();
        DB::table('teacher_submissions')->insert([
            'id' => $submissionId,
            'created_at' => now(),
            'census_no' => $censusNo,
            'school_name' => $schoolName,
            'nic' => $nic,
            'full_name' => $fullName,
            'mobile' => $request->input('mobile'),
            'data' => json_encode($allInputs),
        ]);

        // 2. Save or update Teacher model
        $teacher = Teacher::where('nic', $nic)->first();
        $teacherData = [
            'schoolId' => $school ? $school->id : null,
            'census_no' => $censusNo,
            'nic' => $nic,
            'paysheet' => $request->input('paysheet'),
            'title' => $request->input('title'),
            'personal_file_no' => $request->input('personal_file_no'),
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
            'email' => $request->input('email'),
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
            'otherschool_census' => $otherSchoolCensus,
            'otherschool_days' => $otherDays,
            'position' => $request->input('position'),
            'salaryby' => $request->input('salaryby'),
            'servicebased' => $request->input('servicebased'),
            'salarysource' => $request->input('salarysource'),
            'form_data' => $allInputs,
            'updatedByUserId' => auth()->id() ?? null,
        ];

        $isPrincipal = Teacher::isPrincipalPosition($request->input('position'));
        $targetRole = $isPrincipal ? User::ROLE_SCHOOL_ADMIN : User::ROLE_TEACHER;

        if ($teacher) {
            $teacher->update($teacherData);
            if ($teacher->userId) {
                $linkedUser = User::find($teacher->userId);
                if ($linkedUser && $isPrincipal) {
                    $linkedUser->update([
                        'role' => User::ROLE_SCHOOL_ADMIN,
                        'schoolId' => $school ? $school->id : $linkedUser->schoolId,
                    ]);
                }
            }
        } else {
            // Create user account if doesn't exist
            $userEmail = $request->input('email') ?: ($nic . '@wilgamuwa.com');
            $user = User::where('email', $userEmail)->first();
            if (!$user) {
                $user = User::create([
                    'name' => $fullName,
                    'email' => $userEmail,
                    'password' => Hash::make('password123'),
                    'role' => $targetRole,
                    'schoolId' => $school ? $school->id : null,
                    'isActive' => true,
                ]);
            } else if ($isPrincipal) {
                $user->update([
                    'role' => User::ROLE_SCHOOL_ADMIN,
                    'schoolId' => $school ? $school->id : $user->schoolId,
                ]);
            }
            $teacherData['id'] = (string) Str::ulid();
            $teacherData['userId'] = $user->id;
            $teacherData['createdByUserId'] = auth()->id() ?? $user->id;
            $teacher = Teacher::create($teacherData);
        }

        // Ensure principal is automatically assigned to the related school
        if ($school && $isPrincipal) {
            Teacher::syncPrincipalToSchool(
                $school,
                $request->input('position'),
                $fullName,
                $nic,
                $request->input('mobile') ?: $request->input('phone')
            );
        }

        return view('teacher-form-success', [
            'teacher' => $teacher,
            'values' => $allInputs,
            'schoolName' => $schoolName,
            'otherSchoolName' => $otherSchoolName,
        ]);
    }
}
