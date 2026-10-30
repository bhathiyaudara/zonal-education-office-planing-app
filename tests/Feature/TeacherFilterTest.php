<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TeacherFilterTest extends TestCase
{
    private School $school;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('school', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('censusNo')->nullable();
            $table->string('eduDivision')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('teacher', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('schoolId')->nullable();
            $table->string('nameWithInitials')->nullable();
            $table->string('fullName')->nullable();
            $table->string('nic')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('position')->nullable();
            $table->string('currentPosition')->nullable();
            $table->string('service')->nullable();
            $table->string('appointmentType')->nullable();
            $table->string('serviceGrade')->nullable();
            $table->string('class_grade')->nullable();
            $table->string('appointmentMedium')->nullable();
            $table->string('appt_medium')->nullable();
            $table->string('appointmentSubject')->nullable();
            $table->string('appt_code')->nullable();
            $table->string('t1_code')->nullable();
            $table->dateTime('dateOfBirth')->nullable();
            $table->dateTime('firstappoint')->nullable();
            $table->dateTime('appointmentDate')->nullable();
            $table->dateTime('serviceGradeAppointedDate')->nullable();
            $table->dateTime('schappoint')->nullable();
            $table->dateTime('joinedCurrentSchoolDate')->nullable();
            $table->string('fileNumber')->nullable();
            $table->string('paysheet')->nullable();
            $table->string('personal_file_no')->nullable();
            $table->string('otherschool')->nullable();
            $table->string('otherschool_census')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });


        $this->school = School::create([
            'id' => 'sch-wilgamuwa',
            'name' => 'Wilgamuwa Central College',
            'censusNo' => '09432',
            'eduDivision' => 'Wilgamuwa',
        ]);
    }

    private function signInZonalAdmin(): void
    {
        $this->actingAs(new User([
            'id' => 'zonal-admin',
            'name' => 'Zonal Admin',
            'email' => 'admin@zeowilgamuwa.lk',
            'role' => User::ROLE_ZONAL_ADMIN,
            'isActive' => true,
        ]));
    }

    public function test_teacher_page_has_appointment_subject_teaching_subject_and_8_years_filters(): void
    {
        $this->signInZonalAdmin();

        // Teacher 1: 10 years at school, English appt, Maths teaching
        Teacher::create([
            'id' => 't1',
            'schoolId' => $this->school->id,
            'nameWithInitials' => 'K.M. Perera',
            'fullName' => 'Kamal Perera',
            'nic' => '198012345678',
            'gender' => 'Male',
            'appointmentSubject' => '231 - English',
            'appt_code' => '231 - English',
            't1_code' => '232 - Maths',
            'schappoint' => Carbon::now()->subYears(10),
        ]);

        // Teacher 2: 3 years at school, Science appt, Science teaching
        Teacher::create([
            'id' => 't2',
            'schoolId' => $this->school->id,
            'nameWithInitials' => 'S.D. Silva',
            'fullName' => 'Sunil Silva',
            'nic' => '199087654321',
            'gender' => 'Male',
            'appointmentSubject' => '234 - Science',
            'appt_code' => '234 - Science',
            't1_code' => '234 - Science',
            'schappoint' => Carbon::now()->subYears(3),
        ]);

        $response = $this->get(route('zonal.teachers'));
        $response->assertOk();

        // 1. Verify old generic filter "Teaching Subject / Specialization" is removed
        $response->assertDontSee('<label class="zems-input-label">Teaching Subject / Specialization</label>', false);
        $response->assertDontSee('id="filterSubject"', false);

        // 2. Verify new "Appointment Subject" filter is present
        $response->assertSee('Appointment Subject / පත්වීම් විෂය');
        $response->assertSee('id="filterAppointmentSubject"', false);

        // 3. Verify new "First Most Teaching Subject" filter is present
        $response->assertSee('First Most Teaching Subject / ප්‍රධාන ඉගැන්වීම් විෂය');
        $response->assertSee('id="filterFirstMostTeachingSubject"', false);

        // 4. Verify new "Service at Current School" (>= 8 years) filter is present
        $response->assertSee('id="filterCurrentSchoolService"', false);
        $response->assertSee('8 Years or More (&ge; 8 Years)', false);

        // 5. Verify quick filter chip for >= 8 years in school
        $response->assertSee('id="chip-service_8_plus"', false);

        // 6. Verify row attributes for T1 (10 years) and T2 (3 years)
        $response->assertSee('data-appointment-subject="231 - english"', false);
        $response->assertSee('data-teaching-subject="232 - maths"', false);
        $response->assertSee('data-years-at-school="10"', false);
        $response->assertSee('data-years-at-school="3"', false);

        // 7. Verify badge for T1 (>= 8 years at school)
        $response->assertSee('&ge;8 yrs at school (10y)', false);
    }
}
