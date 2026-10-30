<?php

namespace App\Http\Controllers\Zonal;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use App\Services\ExportService;
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

        $schools = School::where('isActive', true)->orderBy('name')->get(['id', 'name', 'censusNo', 'eduDivision']);

        // Compute stats
        $totalTeachers = $teachers->count();

        $wilgamuwaCount = $teachers->filter(function ($t) {
            return strcasecmp($t->school->eduDivision ?? '', 'Wilgamuwa') === 0;
        })->count();

        $laggalaCount = $teachers->filter(function ($t) {
            return strcasecmp($t->school->eduDivision ?? '', 'Laggala') === 0;
        })->count();

        $primaryCount = $teachers->filter(function ($t) {
            $s = ($t->appt_code ?? '') . ' ' . ($t->t1_code ?? '') . ' ' . ($t->appointmentSubject ?? '');
            return stripos($s, 'Primary') !== false || stripos($s, '101') !== false;
        })->count();

        $maleCount = $teachers->filter(function ($t) {
            $g = $t->gender ?? '';
            return stripos($g, 'Male') !== false && stripos($g, 'Female') === false;
        })->count();

        $femaleCount = $teachers->filter(function ($t) {
            return stripos($t->gender ?? '', 'Female') !== false;
        })->count();

        // Retiring within 5 years (Retirement age: 60)
        $currentYear = now()->year;
        $retiringCount = $teachers->filter(function ($t) use ($currentYear) {
            if (!$t->dateOfBirth) return false;
            $birthYear = ($t->dateOfBirth instanceof \Carbon\Carbon)
                ? $t->dateOfBirth->year
                : (int)substr((string)$t->dateOfBirth, 0, 4);
            if ($birthYear < 1950 || $birthYear > $currentYear) return false;
            $yearsToRetirement = 60 - ($currentYear - $birthYear);
            return $yearsToRetirement > 0 && $yearsToRetirement <= 5;
        })->count();

        // SLPS (Principals) grade counts and Acting Principals
        $slpsGrade1 = $teachers->filter(function ($t) {
            $g = $t->serviceGrade ?: $t->class_grade;
            $s = $t->service ?: $t->appointmentType;
            $p = $t->position ?: $t->currentPosition;
            $isSLPS = stripos($s ?? '', 'Principal') !== false || stripos($p ?? '', 'Principal') !== false;
            return $isSLPS && stripos($g ?? '', 'Grade I') !== false && stripos($g ?? '', 'Grade II') === false && stripos($g ?? '', 'Grade III') === false;
        })->count();

        $slpsGrade2 = $teachers->filter(function ($t) {
            $g = $t->serviceGrade ?: $t->class_grade;
            $s = $t->service ?: $t->appointmentType;
            $p = $t->position ?: $t->currentPosition;
            $isSLPS = stripos($s ?? '', 'Principal') !== false || stripos($p ?? '', 'Principal') !== false;
            return $isSLPS && stripos($g ?? '', 'Grade II') !== false && stripos($g ?? '', 'Grade III') === false;
        })->count();

        $slpsGrade3 = $teachers->filter(function ($t) {
            $g = $t->serviceGrade ?: $t->class_grade;
            $s = $t->service ?: $t->appointmentType;
            $p = $t->position ?: $t->currentPosition;
            $isSLPS = stripos($s ?? '', 'Principal') !== false || stripos($p ?? '', 'Principal') !== false;
            return $isSLPS && stripos($g ?? '', 'Grade III') !== false;
        })->count();

        $actingPrincipals = $teachers->filter(function ($t) {
            $p = $t->position ?: $t->currentPosition;
            return stripos($p ?? '', 'Acting') !== false;
        })->count();

        $totalPrincipals = $teachers->filter(function ($t) {
            return Teacher::isPrincipalPosition($t->position ?: $t->currentPosition);
        })->count();

        // Teachers working at current school >= 8 years
        $now = now();
        $eightYearsAtSchoolCount = $teachers->filter(function ($t) use ($now) {
            $d = $t->schappoint ?? $t->joinedCurrentSchoolDate;
            if (!$d) return false;
            $dt = ($d instanceof \Carbon\Carbon) ? $d : \Carbon\Carbon::parse($d);
            return $dt->diffInYears($now) >= 8;
        })->count();

        // Compile comprehensive list of all subjects from TeacherOptions and teacher records
        $subjectOptions = TeacherOptions::codes() ?: [];
        $dbAppt = $teachers->pluck('appointmentSubject')->filter()->all();
        $dbApptCode = $teachers->pluck('appt_code')->filter()->all();
        $dbT1 = $teachers->pluck('t1_code')->filter()->all();

        $allSubjectsList = array_values(array_filter(array_unique(array_merge(
            $subjectOptions,
            $dbAppt,
            $dbApptCode,
            $dbT1
        )), function ($s) {
            $trimmed = trim((string)$s);
            return $trimmed !== '' && $trimmed !== '0 -' && $trimmed !== '0';
        }));
        sort($allSubjectsList, SORT_NATURAL | SORT_FLAG_CASE);

        return view('dashboard.zonal.teachers.index', compact(
            'teachers', 'schools', 'totalTeachers', 'maleCount', 'femaleCount',
            'retiringCount', 'slpsGrade3', 'slpsGrade2', 'slpsGrade1', 'actingPrincipals', 'totalPrincipals',
            'wilgamuwaCount', 'laggalaCount', 'primaryCount', 'allSubjectsList', 'eightYearsAtSchoolCount'
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

        $isPrincipal = Teacher::isPrincipalPosition($request->input('position'));
        $targetRole = $isPrincipal ? User::ROLE_SCHOOL_ADMIN : User::ROLE_TEACHER;

        // Create User account
        $userEmail = $request->input('email') ?: ($nic . '@wilgamuwa.com');
        $user = User::create([
            'name' => $nameWithInitials,
            'email' => $userEmail,
            'password' => Hash::make('password123'),
            'role' => $targetRole,
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

        // Ensure principal is automatically assigned to the related school
        if ($schoolId && $isPrincipal) {
            $sc = School::find($schoolId);
            if ($sc) {
                Teacher::syncPrincipalToSchool($sc, $request->input('position'), $nameWithInitials, $nic, $request->input('mobile') ?: $request->input('phone'), $user);
            }
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

        $isPrincipal = Teacher::isPrincipalPosition($request->input('position'));

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
                if ($isPrincipal) {
                    $user->role = User::ROLE_SCHOOL_ADMIN;
                }
                $user->save();
            }
        }

        // Ensure principal is automatically assigned to the related school
        $activeSchoolId = $updateData['schoolId'] ?: $teacher->schoolId;
        if ($activeSchoolId && $isPrincipal) {
            $sc = School::find($activeSchoolId);
            if ($sc) {
                Teacher::syncPrincipalToSchool($sc, $request->input('position'), $nameWithInitials, $nic, $request->input('mobile') ?: $request->input('phone'), $user ?? null);
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

    /**
     * Export teachers matching active filters with ALL collected database columns.
     */
    public function export(Request $request)
    {
        $query = Teacher::with(['school', 'spouseSchool', 'subjects.subject'])
            ->leftJoin('school', 'teacher.schoolId', '=', 'school.id')
            ->select('teacher.*');

        // Filter by specific IDs (from client-side active filter selection)
        if ($request->filled('ids')) {
            $ids = is_array($request->get('ids')) ? $request->get('ids') : explode(',', $request->get('ids'));
            $ids = array_filter(array_map('trim', $ids));
            if (!empty($ids)) {
                $query->whereIn('teacher.id', $ids);
            }
        } else {
            // Apply all individual filter parameters

            // 1. Division
            if ($division = $request->get('division')) {
                $query->where('school.eduDivision', $division);
            }

            // 2. School
            if ($schoolId = $request->get('school_id') ?: $request->get('school')) {
                $query->where('teacher.schoolId', $schoolId);
            }

            // 3. Position
            if ($pos = $request->get('position')) {
                if ($pos === 'principals') {
                    $query->where(function ($q) {
                        $q->where('teacher.position', 'like', '1 - Principal%')
                          ->orWhere('teacher.position', 'like', '2 - Acting Principal%')
                          ->orWhere('teacher.currentPosition', 'like', '1 - Principal%')
                          ->orWhere('teacher.currentPosition', 'like', '2 - Acting Principal%')
                          ->orWhere('teacher.service', 'like', '%Principal%');
                    });
                } else {
                    $query->where(function ($q) use ($pos) {
                        $q->where('teacher.position', 'like', "%{$pos}%")
                          ->orWhere('teacher.currentPosition', 'like', "%{$pos}%");
                    });
                }
            }

            // 4. Service
            if ($svc = $request->get('service')) {
                $query->where(function ($q) use ($svc) {
                    $q->where('teacher.service', 'like', "%{$svc}%")
                      ->orWhere('teacher.appointmentType', 'like', "%{$svc}%");
                });
            }

            // 5. Grade / Class
            if ($grd = $request->get('grade')) {
                $query->where(function ($q) use ($grd) {
                    $q->where('teacher.serviceGrade', 'like', "%{$grd}%")
                      ->orWhere('teacher.class_grade', 'like', "%{$grd}%");
                });
            }

            // 6. Retirement Timeline
            if ($retire = $request->get('retirement')) {
                $currentYear = (int)date('Y');
                if ($retire === '1') {
                    $query->whereNotNull('teacher.dateOfBirth')
                          ->where('teacher.dateOfBirth', '<=', ($currentYear - 59) . '-12-31')
                          ->where('teacher.dateOfBirth', '>=', ($currentYear - 60) . '-01-01');
                } elseif ($retire === '3') {
                    $query->whereNotNull('teacher.dateOfBirth')
                          ->where('teacher.dateOfBirth', '<=', ($currentYear - 57) . '-12-31')
                          ->where('teacher.dateOfBirth', '>=', ($currentYear - 60) . '-01-01');
                } elseif ($retire === '5' || $retire === 'retiring') {
                    $query->whereNotNull('teacher.dateOfBirth')
                          ->where('teacher.dateOfBirth', '<=', ($currentYear - 55) . '-12-31')
                          ->where('teacher.dateOfBirth', '>=', ($currentYear - 60) . '-01-01');
                } elseif ($retire === 'over_5') {
                    $query->whereNotNull('teacher.dateOfBirth')
                          ->where('teacher.dateOfBirth', '>', ($currentYear - 55) . '-12-31');
                }
            }

            // 7. Appointment Subject
            if ($apptSubj = $request->get('appt_subject') ?: $request->get('appointment_subject')) {
                $query->where(function ($q) use ($apptSubj) {
                    $q->where('teacher.appt_code', 'like', "%{$apptSubj}%")
                      ->orWhere('teacher.appointmentSubject', 'like', "%{$apptSubj}%");
                });
            }

            // 8. Teaching Subject
            if ($teachSubj = $request->get('teaching_subject')) {
                $query->where('teacher.t1_code', 'like', "%{$teachSubj}%");
            }

            // 9. Current School Service (>=8 Yrs or <8 Yrs)
            if ($schoolSvc = $request->get('school_service')) {
                $cutoff = now()->subYears(8)->format('Y-m-d');
                if ($schoolSvc === '8_plus' || $schoolSvc === 'ge8') {
                    $query->where(function ($q) use ($cutoff) {
                        $q->where(function ($sub) use ($cutoff) {
                            $sub->whereNotNull('teacher.schappoint')->where('teacher.schappoint', '<=', $cutoff);
                        })->orWhere(function ($sub) use ($cutoff) {
                            $sub->whereNull('teacher.schappoint')->whereNotNull('teacher.joinedCurrentSchoolDate')->where('teacher.joinedCurrentSchoolDate', '<=', $cutoff);
                        });
                    });
                } elseif ($schoolSvc === 'under_8' || $schoolSvc === 'lt8') {
                    $query->where(function ($q) use ($cutoff) {
                        $q->where(function ($sub) use ($cutoff) {
                            $sub->whereNotNull('teacher.schappoint')->where('teacher.schappoint', '>', $cutoff);
                        })->orWhere(function ($sub) use ($cutoff) {
                            $sub->whereNull('teacher.schappoint')->whereNotNull('teacher.joinedCurrentSchoolDate')->where('teacher.joinedCurrentSchoolDate', '>', $cutoff);
                        });
                    });
                }
            }

            // 10. Gender
            if ($gender = $request->get('gender')) {
                $query->where('teacher.gender', 'like', "{$gender}%");
            }

            // 11. Medium
            if ($med = $request->get('medium')) {
                $query->where(function ($q) use ($med) {
                    $q->where('teacher.appt_medium', 'like', "%{$med}%")
                      ->orWhere('teacher.appointmentMedium', 'like', "%{$med}%");
                });
            }

            // 12. Attachment
            if ($attach = $request->get('attachment')) {
                if ($attach === 'attached_any') {
                    $query->where(function ($q) {
                        $q->where('teacher.otherschool', 'like', 'Yes%')
                          ->orWhereNotNull('teacher.otherschool_census');
                    });
                } elseif ($attach === 'not_attached') {
                    $query->where(function ($q) {
                        $q->whereNull('teacher.otherschool')
                          ->orWhere('teacher.otherschool', 'like', 'No%')
                          ->orWhere('teacher.otherschool', '');
                    });
                } elseif ($attach === 'attached_office') {
                    $query->where(function ($q) {
                        $q->where('teacher.otherschool_census', 'like', '%ZEO%')
                          ->orWhere('teacher.otherschool_census', 'like', '%PDC%')
                          ->orWhere('teacher.otherschool_census', 'like', '%DEO%')
                          ->orWhere('teacher.otherschool_census', 'like', '%ITDLH%');
                    });
                } elseif ($attach === 'attached_school') {
                    $query->where(function ($q) {
                        $q->whereNotNull('teacher.otherschool_census')
                          ->where('teacher.otherschool_census', 'not like', '%ZEO%')
                          ->where('teacher.otherschool_census', 'not like', '%PDC%')
                          ->where('teacher.otherschool_census', 'not like', '%DEO%')
                          ->where('teacher.otherschool_census', 'not like', '%ITDLH%');
                    });
                }
            }

            // 13. Search
            if ($search = trim($request->get('search', ''))) {
                $query->where(function ($q) use ($search) {
                    $q->where('teacher.fullName', 'like', "%{$search}%")
                      ->orWhere('teacher.nameWithInitials', 'like', "%{$search}%")
                      ->orWhere('teacher.nic', 'like', "%{$search}%")
                      ->orWhere('teacher.phone', 'like', "%{$search}%")
                      ->orWhere('teacher.whatsapp', 'like', "%{$search}%")
                      ->orWhere('teacher.personal_file_no', 'like', "%{$search}%")
                      ->orWhere('teacher.fileNumber', 'like', "%{$search}%")
                      ->orWhere('teacher.paysheet', 'like', "%{$search}%")
                      ->orWhere('teacher.email', 'like', "%{$search}%")
                      ->orWhere('school.name', 'like', "%{$search}%")
                      ->orWhere('school.censusNo', 'like', "%{$search}%");
                });
            }
        }

        $teachers = $query->orderBy('school.name')->orderBy('teacher.nameWithInitials')->get();

        $filename = 'Wilgamuwa_Teachers_Export_' . date('Y_m_d_His') . '.csv';
        return ExportService::exportTeachers($teachers, $filename);
    }
}
