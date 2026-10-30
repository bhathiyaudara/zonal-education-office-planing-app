<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use App\Services\StudentCensusService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StudentCensusAndSubjectsTest extends TestCase
{
    protected User $zonalAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('school', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('nameSinhala')->nullable();
            $table->string('category')->nullable();
            $table->string('censusNo')->nullable()->unique();
            $table->string('address')->nullable();
            $table->string('schoolType')->nullable();
            $table->string('eduZone')->nullable();
            $table->string('eduDivision')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('buildings', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('schoolId');
            $table->string('buildingName');
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('school_sanitation', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('schoolId')->unique();
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
            $table->string('classes')->nullable();
            $table->text('classDetails')->nullable();
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('teacher', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('schoolId')->nullable();
            $table->string('fullName');
            $table->string('gender')->nullable();
            $table->string('nic')->nullable();
            $table->string('phone')->nullable();
            $table->string('appointmentType')->nullable();
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('user', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role');
            $table->string('schoolId')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });

        $this->zonalAdmin = User::create([
            'id' => 'u-admin-test',
            'name' => 'Zonal Admin',
            'email' => 'admin@wilgamuwa.lk',
            'password' => bcrypt('secret'),
            'role' => User::ROLE_ZONAL_ADMIN,
        ]);
    }

    public function test_student_census_service_returns_accurate_kpis(): void
    {
        $kpis = StudentCensusService::getZonalKPIs();

        $this->assertGreaterThan(9000, $kpis['totalStudents']);
        $this->assertEquals(4153, $kpis['primaryStudents']);
        $this->assertEquals(4768, $kpis['secondaryStudents']);
        $this->assertEquals(759, $kpis['collegiateStudents']);
        $this->assertEquals(20, $kpis['specialEdStudents']);
        $this->assertEquals(557, $kpis['pirivenaTotal']);
        $this->assertEquals(45, $kpis['totalSchools']);
    }

    public function test_ol_and_al_subjects_loaded_with_translations(): void
    {
        $ol = StudentCensusService::getOlSubjects();
        $this->assertNotEmpty($ol);
        $this->assertArrayHasKey('subject_en', $ol[0]);

        $al = StudentCensusService::getAlSubjects();
        $this->assertNotEmpty($al);
        $this->assertArrayHasKey('subject_en', $al[0]);

        $streams = StudentCensusService::getAlStreams();
        $this->assertNotEmpty($streams);
        $this->assertArrayHasKey('stream_en', $streams[0]);
    }

    public function test_student_overview_page_renders_cleanly_with_all_tabs(): void
    {
        $this->actingAs($this->zonalAdmin);

        School::create([
            'id' => 'sch-1',
            'name' => 'Naminioya Central College',
            'censusNo' => '04119',
            'eduDivision' => 'Wilgamuwa',
        ]);

        $response = $this->get(route('zonal.schools.students'));
        $response->assertOk();
        $response->assertSee('Student & Subject Analytics', false);
        $response->assertSee('School & Grade Enrollment', false);
        $response->assertSee('Pirivena Institutions');
        $response->assertSee('Naminioya Central College');
    }

    public function test_exports_for_all_datasets(): void
    {
        $this->actingAs($this->zonalAdmin);

        School::create([
            'id' => 'sch-export',
            'name' => 'Test School Export',
            'censusNo' => '04143',
            'eduDivision' => 'Laggala',
        ]);

        // Grades export
        $r1 = $this->get(route('zonal.schools.students.export', ['type' => 'grades']));
        $r1->assertOk();
        $content1 = $r1->streamedContent();
        $this->assertStringContainsString('School Name', $content1);

        // OL subjects export
        $r2 = $this->get(route('zonal.schools.students.export', ['type' => 'ol_subjects']));
        $r2->assertOk();
        $content2 = $r2->streamedContent();
        $this->assertStringContainsString('Subject (Sinhala)', $content2);
        $this->assertStringContainsString('Zonal Total Students', $content2);

        // AL subjects export
        $r3 = $this->get(route('zonal.schools.students.export', ['type' => 'al_subjects']));
        $r3->assertOk();
        $content3 = $r3->streamedContent();
        $this->assertStringContainsString('Subject (Sinhala)', $content3);

        // AL streams export
        $r4 = $this->get(route('zonal.schools.students.export', ['type' => 'al_streams']));
        $r4->assertOk();
        $content4 = $r4->streamedContent();
        $this->assertStringContainsString('Stream (Sinhala)', $content4);
    }

    public function test_migration_up_and_down_runs_cleanly(): void
    {
        $migration = require database_path('migrations/2026_10_30_000006_seed_students_census_and_subject_statistics.php');
        $migration->up();

        $this->assertTrue(Schema::hasTable('subject_statistic'));
        $this->assertTrue(Schema::hasTable('pirivena'));

        $migration->down();
        $this->assertFalse(Schema::hasTable('subject_statistic'));
        $this->assertFalse(Schema::hasTable('pirivena'));
    }

    public function test_individual_school_page_renders_with_subject_tabs(): void
    {
        $this->actingAs($this->zonalAdmin);

        $school = School::create([
            'id' => 'sch-hattota',
            'name' => 'CP/WIL/HATTOTA AMUNA MAHA VIDYALAYA',
            'censusNo' => '04143',
            'eduDivision' => 'Wilgamuwa',
        ]);

        $response = $this->get(route('zonal.schools.show', $school->id));
        $response->assertOk();
        $response->assertSee('Student & Subject Analytics', false);
        $response->assertSee('O/L Subjects (Grades 10 & 11)', false);
        $response->assertSee('A/L Subjects & Streams (Grades 12 & 13)', false);
        $response->assertSee('Geography');
        $response->assertSee('Commerce (වාණිජ)');
    }
}
