<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\School;
use App\Models\User;
use App\Models\Teacher;

return new class extends Migration
{
    public function up(): void
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $dataFile = database_path('data/teachers_census_data.json');
        if (!file_exists($dataFile)) {
            $dataFile = base_path('database/data/teachers_census_data.json');
        }
        if (!file_exists($dataFile)) {
            throw new \Exception("Data file not found: $dataFile");
        }

        $records = json_decode(file_get_contents($dataFile), true);
        if (!$records) {
            throw new \Exception("Failed to decode JSON data");
        }

        DB::beginTransaction();
        try {
            // 1. Preload schools mapped by 5-digit censusNo
            $schools = School::all()->keyBy(function ($s) {
                return str_pad(trim($s->censusNo), 5, '0', STR_PAD_LEFT);
            });

            // 2. Preload existing users and teachers by NIC
            $existingTeachers = Teacher::all()->keyBy('nic');
            $existingUsers = User::all()->keyBy('email');

            $passwordHash = Hash::make('password123');
            $schoolsToUpdate = [];

            foreach ($records as $r) {
                $nic = strtoupper(trim($r['nic']));
                $census = str_pad(trim($r['census_no']), 5, '0', STR_PAD_LEFT);
                $isPrincipal = (bool)($r['is_principal'] ?? false);

                // Resolve school
                $school = $schools->get($census);
                if (!$school) {
                    $schoolId = (string) Str::ulid();
                    $school = School::create([
                        'id' => $schoolId,
                        'name' => $r['school_name'] ?: "School $census",
                        'censusNo' => $census,
                        'category' => $r['school_category'] ?? '1.Govt',
                        'schoolType' => $r['school_type'] ?? '2.1C',
                        'eduZone' => $r['edu_zone'] ?? 'Wilgamuwa',
                        'eduDivision' => $r['edu_division'] ?? 'Wilgamuwa',
                        'sanctionedPositions' => $r['staff_base'] ?? 0,
                        'isActive' => true,
                    ]);
                    $schools->put($census, $school);
                } else {
                    $schoolUpdates = [];
                    if (!empty($r['staff_base']) && $school->sanctionedPositions != $r['staff_base']) {
                        $schoolUpdates['sanctionedPositions'] = $r['staff_base'];
                    }
                    if (!empty($r['school_type']) && empty($school->schoolType)) {
                        $schoolUpdates['schoolType'] = $r['school_type'];
                    }
                    if (!empty($r['school_category']) && empty($school->category)) {
                        $schoolUpdates['category'] = $r['school_category'];
                    }
                    if (!empty($schoolUpdates)) {
                        $school->update($schoolUpdates);
                    }
                }

                // If Principal, prepare school principal update
                if ($isPrincipal) {
                    $school->update([
                        'principalName' => $r['name'],
                        'principalNic' => $nic,
                        'principalMobile' => $r['mobile'] ?: $school->principalMobile,
                    ]);
                }

                // Resolve User
                $email = strtolower($nic) . '@wilgamuwa.lk';
                $userRole = $isPrincipal ? User::ROLE_SCHOOL_ADMIN : User::ROLE_TEACHER;
                
                $user = $existingUsers->get($email);
                if (!$user) {
                    $userId = (string) Str::ulid();
                    $user = User::create([
                        'id' => $userId,
                        'name' => $r['name'],
                        'email' => $email,
                        'password' => $passwordHash,
                        'role' => $userRole,
                        'schoolId' => $school->id,
                        'isActive' => true,
                    ]);
                    $existingUsers->put($email, $user);
                } else {
                    $userUpdates = ['schoolId' => $school->id];
                    if ($isPrincipal && $user->role !== User::ROLE_SCHOOL_ADMIN && $user->role !== User::ROLE_SUPER_ADMIN && $user->role !== User::ROLE_ZONAL_ADMIN) {
                        $userUpdates['role'] = User::ROLE_SCHOOL_ADMIN;
                    }
                    $user->update($userUpdates);
                }

                // Teacher data
                $teacherData = [
                    'userId' => $user->id,
                    'schoolId' => $school->id,
                    'census_no' => $census,
                    'nic' => $nic,
                    'paysheet' => $r['paysheet'],
                    'title' => $r['title'],
                    'personal_file_no' => $r['paysheet'],
                    'nameWithInitials' => $r['name'],
                    'fullName' => $r['name'],
                    'dateOfBirth' => $r['dob'],
                    'gender' => $r['gender'],
                    'ethnicity' => $r['ethnicity'],
                    'ethnicity_other' => $r['ethnicity_other'],
                    'religion' => $r['religion'],
                    'religion_other' => $r['religion_other'],
                    'maritalStatus' => $r['marital'],
                    'marital_other' => $r['marital_other'],
                    'address' => $r['school_name'],
                    'phone' => $r['mobile'],
                    'whatsapp' => $r['mobile'],
                    'email' => $email,
                    'eduqua' => $r['eduqua'],
                    'subject_stream' => $r['subject'],
                    'field_study' => $r['field'],
                    'field_study_other' => $r['field_other'],
                    'profqua' => $r['profqua'],
                    'profqua_other' => $r['profqua_other'],
                    'profall' => $r['profall'],
                    'service' => $r['service'],
                    'firstappoint' => $r['firstappoint'],
                    'class_grade' => $r['class'],
                    'clsappoint' => $r['clsappoint'],
                    'schappoint' => $r['schappoint'],
                    'appt_section' => $r['appt_section'],
                    'appt_category' => $r['appt_category'],
                    'appt_code' => $r['appt_code'],
                    'appt_medium' => $r['appt_medium'],
                    'mainduty' => $r['mainduty'],
                    'mainduty_other' => $r['mainduty_other'],
                    'secondduty' => $r['secondduty'],
                    'secondduty_other' => $r['secondduty_other'],
                    't1_section' => $r['t1_section'],
                    't1_category' => $r['t1_category'],
                    't1_code' => $r['t1_code'],
                    't1_medium' => $r['t1_medium'],
                    't1_periods' => $r['t1_periods'],
                    't2_section' => $r['t2_section'],
                    't2_category' => $r['t2_category'],
                    't2_code' => $r['t2_code'],
                    't2_medium' => $r['t2_medium'],
                    't2_periods' => $r['t2_periods'],
                    'able1_section' => $r['able1_section'],
                    'able1_category' => $r['able1_category'],
                    'able1_code' => $r['able1_code'],
                    'able1_medium' => $r['able1_medium'],
                    'able2_section' => $r['able2_section'],
                    'able2_category' => $r['able2_category'],
                    'able2_code' => $r['able2_code'],
                    'able2_medium' => $r['able2_medium'],
                    'position' => $r['position'],
                    'currentPosition' => $r['position'],
                    'salaryby' => $r['salaryby'],
                    'servicebased' => $r['servicebased'],
                    'salarysource' => $r['salarysource'],
                    'appointmentDate' => $r['firstappoint'],
                    'appointmentType' => $r['service'],
                    'appointmentMedium' => $r['appt_medium'],
                    'serviceGrade' => $r['class'],
                    'serviceGradeAppointedDate' => $r['clsappoint'],
                    'joinedCurrentSchoolDate' => $r['schappoint'],
                    'salaryNumber' => $r['paysheet'],
                    'form_data' => $r['raw'] ?? [],
                ];

                $teacher = $existingTeachers->get($nic);
                if ($teacher) {
                    $teacher->update($teacherData);
                } else {
                    $teacherData['id'] = (string) Str::ulid();
                    $newTeacher = Teacher::create($teacherData);
                    $existingTeachers->put($nic, $newTeacher);
                }

                // Record in teacher_submissions
                DB::table('teacher_submissions')->updateOrInsert(
                    ['nic' => $nic],
                    [
                        'id' => (string) Str::ulid(),
                        'created_at' => now(),
                        'census_no' => $census,
                        'school_name' => $r['school_name'] ?: $school->name,
                        'full_name' => $r['name'],
                        'mobile' => $r['mobile'],
                        'data' => json_encode($r['raw'] ?? []),
                    ]
                );
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function down(): void
    {
        // No-op
    }
};
