<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\School;
use App\Models\SchoolSanitation;
use App\Models\StudentCount;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExportAllPagesTest extends TestCase
{
    private School $school1;
    private School $school2;
    private User $zonalAdmin;
    private User $schoolAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        // Build SQLite in-memory test schema
        Schema::create('school', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('nameSinhala')->nullable();
            $table->string('category')->nullable();
            $table->string('censusNo')->nullable();
            $table->string('address')->nullable();
            $table->string('landArea')->nullable();
            $table->string('difficultyCategory')->nullable();
            $table->string('contactNumber')->nullable();
            $table->string('email')->nullable();
            $table->string('schoolType')->nullable();
            $table->string('eduZone')->nullable();
            $table->string('eduDivision')->nullable();
            $table->string('dsDivision')->nullable();
            $table->string('gnDivision')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('gradeStart')->nullable();
            $table->integer('gradeEnd')->nullable();
            $table->integer('sanctionedPositions')->nullable();
            $table->string('principalName')->nullable();
            $table->string('principalNic')->nullable();
            $table->string('principalMobile')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });


        Schema::create('teacher', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('userId')->nullable();
            $table->string('schoolId')->nullable();
            $table->string('census_no')->nullable();
            $table->string('nic')->nullable();
            $table->string('paysheet')->nullable();
            $table->string('title')->nullable();
            $table->string('personal_file_no')->nullable();
            $table->string('nameWithInitials')->nullable();
            $table->string('fullName')->nullable();
            $table->dateTime('dateOfBirth')->nullable();
            $table->string('gender')->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('ethnicity_other')->nullable();
            $table->string('religion')->nullable();
            $table->string('religion_other')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('maritalStatus')->nullable();
            $table->string('marital_other')->nullable();
            $table->string('eduqua')->nullable();
            $table->string('subject_stream')->nullable();
            $table->string('field_study')->nullable();
            $table->string('field_study_other')->nullable();
            $table->string('profqua')->nullable();
            $table->string('profqua_other')->nullable();
            $table->text('profall')->nullable();
            $table->string('service')->nullable();
            $table->dateTime('firstappoint')->nullable();
            $table->string('class_grade')->nullable();
            $table->dateTime('clsappoint')->nullable();
            $table->dateTime('schappoint')->nullable();
            $table->string('appt_section')->nullable();
            $table->string('appt_category')->nullable();
            $table->string('appt_code')->nullable();
            $table->string('appt_medium')->nullable();
            $table->string('mainduty')->nullable();
            $table->string('mainduty_other')->nullable();
            $table->string('secondduty')->nullable();
            $table->string('secondduty_other')->nullable();
            $table->string('t1_section')->nullable();
            $table->string('t1_category')->nullable();
            $table->string('t1_code')->nullable();
            $table->string('t1_medium')->nullable();
            $table->integer('t1_periods')->nullable();
            $table->string('t2_section')->nullable();
            $table->string('t2_category')->nullable();
            $table->string('t2_code')->nullable();
            $table->string('t2_medium')->nullable();
            $table->integer('t2_periods')->nullable();
            $table->string('able1_section')->nullable();
            $table->string('able1_category')->nullable();
            $table->string('able1_code')->nullable();
            $table->string('able1_medium')->nullable();
            $table->string('able2_section')->nullable();
            $table->string('able2_category')->nullable();
            $table->string('able2_code')->nullable();
            $table->string('able2_medium')->nullable();
            $table->string('otherschool')->nullable();
            $table->string('otherschool_census')->nullable();
            $table->string('otherschool_days')->nullable();
            $table->string('position')->nullable();
            $table->string('currentPosition')->nullable();
            $table->string('salaryby')->nullable();
            $table->text('servicebased')->nullable();
            $table->string('salarysource')->nullable();
            $table->boolean('spouseIsTeacherInWilgamuwa')->default(false);
            $table->string('spouseNameWithInitials')->nullable();
            $table->string('spouseNic')->nullable();
            $table->string('spouseSchoolId')->nullable();
            $table->dateTime('appointmentDate')->nullable();
            $table->string('appointmentType')->nullable();
            $table->string('appointmentMedium')->nullable();
            $table->string('serviceGrade')->nullable();
            $table->dateTime('serviceGradeAppointedDate')->nullable();
            $table->dateTime('joinedCurrentSchoolDate')->nullable();
            $table->boolean('isLockedByZonal')->default(false);
            $table->boolean('isLockedBySchool')->default(false);
            $table->string('fileNumber')->nullable();
            $table->string('salaryNumber')->nullable();
            $table->string('appointmentSubject')->nullable();
            $table->string('trainingStatus')->nullable();
            $table->string('natureOfAppointment')->nullable();
            $table->string('salaryPayingSchool')->nullable();
            $table->text('undergraduateDegrees')->nullable();
            $table->string('undergraduateDegreesOther')->nullable();
            $table->text('postgraduateDiplomas')->nullable();
            $table->string('postgraduateDiplomasOther')->nullable();
            $table->text('postgraduateDegrees')->nullable();
            $table->string('postgraduateDegreesOther')->nullable();
            $table->text('additionalTeachingQualifications')->nullable();
            $table->text('pastServiceDetails')->nullable();
            $table->string('appointmentLetterUrl')->nullable();
            $table->text('form_data')->nullable();
            $table->string('createdByUserId')->nullable();
            $table->string('updatedByUserId')->nullable();
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('schoolId');
            $table->string('buildingName');
            $table->string('buildingType')->nullable();
            $table->string('size')->nullable();
            $table->float('lengthFeet')->nullable();
            $table->float('widthFeet')->nullable();
            $table->float('areaSqft')->nullable();
            $table->integer('floors')->default(1);
            $table->integer('constructionYear')->nullable();
            $table->integer('lastRepairYear')->nullable();
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('school_sanitation', function (Blueprint $table) {
            $table->id();
            $table->string('schoolId')->unique();
            $table->integer('reqBoysToilets')->default(0);
            $table->integer('reqGirlsToilets')->default(0);
            $table->integer('reqTeacherToilets')->default(0);
            $table->integer('boysToilets')->default(0);
            $table->integer('girlsToilets')->default(0);
            $table->integer('teacherToilets')->default(0);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('studentcount', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('schoolId');
            $table->string('grade');
            $table->string('academicYear');
            $table->integer('maleCount')->default(0);
            $table->integer('femaleCount')->default(0);
            $table->integer('classCount')->default(0);
            $table->text('classes')->nullable();
            $table->text('classDetails')->nullable();
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });

        // Seed schools
        $this->school1 = School::create([
            'id' => 'sch-wilgamuwa',
            'name' => 'Wilgamuwa Central College',
            'censusNo' => '09341',
            'eduDivision' => 'Wilgamuwa',
            'dsDivision' => 'Wilgamuwa',
            'gnDivision' => 'Wilgamuwa North',
            'difficultyCategory' => 'Difficult (දුෂ්කර)',
            'schoolType' => '1AB',
            'gradeStart' => 1,
            'gradeEnd' => 13,
            'sanctionedPositions' => 45,
            'isActive' => true,
        ]);

        $this->school2 = School::create([
            'id' => 'sch-laggala',
            'name' => 'Laggala Pallegama Maha Vidyalaya',
            'censusNo' => '09342',
            'eduDivision' => 'Laggala',
            'dsDivision' => 'Laggala-Pallegama',
            'gnDivision' => 'Pallegama Central',
            'difficultyCategory' => 'Very Difficult (ඉතා දුෂ්කර)',
            'schoolType' => '1C',
            'gradeStart' => 1,
            'gradeEnd' => 13,
            'sanctionedPositions' => 25,
            'isActive' => true,
        ]);

        // Seed users
        $this->zonalAdmin = new User([
            'id' => 'user-zonal-admin',
            'name' => 'Zonal Director',
            'email' => 'director@zeowilgamuwa.lk',
            'role' => User::ROLE_ZONAL_ADMIN,
        ]);

        $this->schoolAdmin = new User([
            'id' => 'user-school-admin',
            'name' => 'Principal Wilgamuwa',
            'email' => 'principal@wilgamuwa.lk',
            'role' => User::ROLE_SCHOOL_ADMIN,
            'schoolId' => $this->school1->id,
        ]);
    }

    public function test_zonal_teachers_export_contains_all_columns_and_respects_filter(): void
    {
        Teacher::create([
            'id' => 'tch-1',
            'schoolId' => $this->school1->id,
            'nameWithInitials' => 'K.M. Perera',
            'fullName' => 'Kamal Madduma Perera',
            'nic' => '198512345678',
            'gender' => 'Male',
            'appointmentSubject' => 'Science',
            'appt_code' => '014',
            't1_code' => '014 - Science',
            'position' => 'Teacher',
            'dateOfBirth' => '1985-05-15',
            'schappoint' => now()->subYears(10)->format('Y-m-d'),
        ]);

        Teacher::create([
            'id' => 'tch-2',
            'schoolId' => $this->school2->id,
            'nameWithInitials' => 'S.B. Silva',
            'fullName' => 'Sunil Bandara Silva',
            'nic' => '199012345678',
            'gender' => 'Male',
            'appointmentSubject' => 'Mathematics',
            'appt_code' => '013 - Mathematics',
            't1_code' => '013 - Mathematics',
            'position' => 'Teacher',
            'dateOfBirth' => '1990-03-20',
            'schappoint' => now()->subYears(3)->format('Y-m-d'),
        ]);

        // 1. Export all without filter
        $response = $this->actingAs($this->zonalAdmin)->get(route('zonal.teachers.export'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        
        $content = $response->streamedContent();
        $this->assertStringContainsString('Teacher Record ID', $content);
        $this->assertStringContainsString('School Census No', $content);
        $this->assertStringContainsString('Appointment Subject Code', $content);
        $this->assertStringContainsString('Teaching Subject 1 Code & Subject', $content);
        $this->assertStringContainsString('Years at Current School', $content);
        $this->assertStringContainsString('Retirement Date (Age 60)', $content);
        $this->assertStringContainsString('Kamal Madduma Perera', $content);
        $this->assertStringContainsString('Sunil Bandara Silva', $content);

        // 2. Export with filter by division (Wilgamuwa)
        $filteredResponse = $this->actingAs($this->zonalAdmin)->get(route('zonal.teachers.export', [
            'division' => 'Wilgamuwa',
        ]));
        $filteredContent = $filteredResponse->streamedContent();
        $this->assertStringContainsString('Kamal Madduma Perera', $filteredContent);
        $this->assertStringNotContainsString('Sunil Bandara Silva', $filteredContent);

        // 3. Export with filter by appointment subject (Mathematics)
        $mathResponse = $this->actingAs($this->zonalAdmin)->get(route('zonal.teachers.export', [
            'appt_subject' => 'Mathematics',
        ]));
        $mathContent = $mathResponse->streamedContent();
        $this->assertStringNotContainsString('Kamal Madduma Perera', $mathContent);
        $this->assertStringContainsString('Sunil Bandara Silva', $mathContent);
    }

    public function test_zonal_teachers_export_respects_ids_filter(): void
    {
        Teacher::create([
            'id' => 'tch-1',
            'schoolId' => $this->school1->id,
            'nameWithInitials' => 'K.M. Perera',
            'fullName' => 'Kamal Madduma Perera',
            'nic' => '198512345678',
        ]);
        Teacher::create([
            'id' => 'tch-2',
            'schoolId' => $this->school2->id,
            'nameWithInitials' => 'S.B. Silva',
            'fullName' => 'Sunil Bandara Silva',
            'nic' => '199012345678',
        ]);

        $response = $this->actingAs($this->zonalAdmin)->post(route('zonal.teachers.export'), [
            'ids' => 'tch-1',
        ]);
        $content = $response->streamedContent();
        $this->assertStringContainsString('Kamal Madduma Perera', $content);
        $this->assertStringNotContainsString('Sunil Bandara Silva', $content);
    }

    public function test_school_teachers_export_restricts_to_own_school(): void
    {
        Teacher::create([
            'id' => 'tch-wilgamuwa',
            'schoolId' => $this->school1->id,
            'nameWithInitials' => 'K.M. Perera',
            'fullName' => 'Kamal Perera',
            'nic' => '198512345678',
        ]);
        Teacher::create([
            'id' => 'tch-laggala',
            'schoolId' => $this->school2->id,
            'nameWithInitials' => 'S.B. Silva',
            'fullName' => 'Sunil Silva',
            'nic' => '199012345678',
        ]);

        $response = $this->actingAs($this->schoolAdmin)->get(route('school.teachers.export'));
        $response->assertStatus(200);
        $content = $response->streamedContent();

        $this->assertStringContainsString('Teacher Record ID', $content);
        $this->assertStringContainsString('Kamal Perera', $content);
        $this->assertStringNotContainsString('Sunil Silva', $content);
    }

    public function test_zonal_buildings_export_contains_all_columns_and_respects_renovation_filter(): void
    {
        Building::create([
            'schoolId' => $this->school1->id,
            'buildingName' => 'Main Hall 1',
            'buildingType' => 'Learning / ඉගෙනුම්',
            'lengthFeet' => 100,
            'widthFeet' => 30,
            'areaSqft' => 3000,
            'floors' => 1,
            'constructionYear' => 1990,
            'lastRepairYear' => 2005, // 21 years ago -> needs renovation
            'status' => 'Needs Major Renovation (ප්‍රධාන අලුත්වැඩියා අවශ්‍යයි)',
            'notes' => 'Roof requires immediate overhaul',
        ]);

        Building::create([
            'schoolId' => $this->school2->id,
            'buildingName' => 'New Science Lab',
            'buildingType' => 'Learning / ඉගෙනුම්',
            'lengthFeet' => 60,
            'widthFeet' => 25,
            'areaSqft' => 1500,
            'floors' => 2,
            'constructionYear' => 2020,
            'lastRepairYear' => 2024, // Recent -> doesn't need renovation
            'status' => 'Good / Usable (හොඳයි / භාවිතයට සුදුසුයි)',
        ]);

        // 1. Export all buildings
        $response = $this->actingAs($this->zonalAdmin)->get(route('zonal.buildings.export'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('Building ID', $content);
        $this->assertStringContainsString('School Census No', $content);
        $this->assertStringContainsString('Length (Feet)', $content);
        $this->assertStringContainsString('Area (Sq. Ft.)', $content);
        $this->assertStringContainsString('Needs Renovation (>=10 Yrs)', $content);
        $this->assertStringContainsString('Main Hall 1', $content);
        $this->assertStringContainsString('New Science Lab', $content);

        // 2. Export only buildings needing renovation
        $renovResponse = $this->actingAs($this->zonalAdmin)->get(route('zonal.buildings.export', [
            'renovation_needed' => 1,
        ]));
        $renovContent = $renovResponse->streamedContent();
        $this->assertStringContainsString('Main Hall 1', $renovContent);
        $this->assertStringNotContainsString('New Science Lab', $renovContent);
    }

    public function test_school_buildings_export_restricts_to_own_school(): void
    {
        Building::create([
            'schoolId' => $this->school1->id,
            'buildingName' => 'Wilgamuwa Primary Block',
        ]);
        Building::create([
            'schoolId' => $this->school2->id,
            'buildingName' => 'Laggala Admin Block',
        ]);

        $response = $this->actingAs($this->schoolAdmin)->get(route('school.buildings.export'));
        $response->assertStatus(200);
        $content = $response->streamedContent();

        $this->assertStringContainsString('Wilgamuwa Primary Block', $content);
        $this->assertStringNotContainsString('Laggala Admin Block', $content);
    }

    public function test_zonal_schools_export_contains_all_columns(): void
    {
        $response = $this->actingAs($this->zonalAdmin)->get(route('zonal.schools.export'));
        $response->assertStatus(200);
        $content = $response->streamedContent();

        $this->assertStringContainsString('School ID', $content);
        $this->assertStringContainsString('Census No', $content);
        $this->assertStringContainsString('DS Division (DSD)', $content);
        $this->assertStringContainsString('GN Division (GND)', $content);
        $this->assertStringContainsString('School Difficulty Category', $content);
        $this->assertStringContainsString('Sanctioned Teacher Positions', $content);
        $this->assertStringContainsString('Wilgamuwa Central College', $content);
        $this->assertStringContainsString('Laggala Pallegama Maha Vidyalaya', $content);
    }

    public function test_student_overview_export_contains_all_columns_and_grade_breakdowns(): void
    {
        $currentYear = date('Y');
        $academicYear = $currentYear . '/' . ($currentYear + 1);

        StudentCount::create([
            'id' => 'sc-1',
            'schoolId' => $this->school1->id,
            'grade' => '1',
            'academicYear' => $academicYear,
            'maleCount' => 15,
            'femaleCount' => 12,
            'classCount' => 1,
        ]);

        StudentCount::create([
            'id' => 'sc-2',
            'schoolId' => $this->school1->id,
            'grade' => '6',
            'academicYear' => $academicYear,
            'maleCount' => 20,
            'femaleCount' => 18,
            'classCount' => 1,
        ]);

        $response = $this->actingAs($this->zonalAdmin)->get(route('zonal.schools.students.export'));
        $response->assertStatus(200);
        $content = $response->streamedContent();

        $this->assertStringContainsString('School ID', $content);
        $this->assertStringContainsString('Total Enrolled Students', $content);
        $this->assertStringContainsString('Primary Section Total Students', $content);
        $this->assertStringContainsString('Grade 1 Male', $content);
        $this->assertStringContainsString('Grade 1 Female', $content);
        $this->assertStringContainsString('Grade 1 Total', $content);
        $this->assertStringContainsString('Junior Secondary Total Students', $content);
        $this->assertStringContainsString('Grade 6 Male', $content);
        $this->assertStringContainsString('Wilgamuwa Central College', $content);
    }
}
