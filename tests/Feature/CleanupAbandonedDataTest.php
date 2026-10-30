<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CleanupAbandonedDataTest extends TestCase
{
    public function test_cleanup_migration_drops_tables_and_columns_cleanly(): void
    {
        // 1. Create dummy dummy abandoned tables
        Schema::create('teachersubject', function (Blueprint $table) {
            $table->string('id')->primary();
        });
        Schema::create('subjectstatistic', function (Blueprint $table) {
            $table->string('id')->primary();
        });
        Schema::create('subject', function (Blueprint $table) {
            $table->string('id')->primary();
        });
        Schema::create('transferrequest', function (Blueprint $table) {
            $table->string('id')->primary();
        });
        Schema::create('salaryrequest', function (Blueprint $table) {
            $table->string('id')->primary();
        });
        Schema::create('profileeditrequest', function (Blueprint $table) {
            $table->string('id')->primary();
        });
        Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary();
        });

        // 2. Create teacher table with legacy columns
        Schema::create('teacher', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('fullName')->nullable();
            $table->string('position')->nullable();
            $table->string('firstappoint')->nullable();
            // Legacy columns to drop
            $table->string('nameWithInitials')->nullable();
            $table->string('currentPosition')->nullable();
            $table->string('appointmentDate')->nullable();
            $table->string('spouseSchoolId')->nullable();
            $table->string('trainingStatus')->nullable();
        });

        $this->assertTrue(Schema::hasTable('teachersubject'));
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasColumn('teacher', 'nameWithInitials'));
        $this->assertTrue(Schema::hasColumn('teacher', 'currentPosition'));

        // 3. Run migration
        $migration = require database_path('migrations/2026_10_30_000005_cleanup_abandoned_tables_and_columns.php');
        $migration->up();

        // 4. Assert tables were dropped
        $this->assertFalse(Schema::hasTable('teachersubject'));
        $this->assertFalse(Schema::hasTable('subjectstatistic'));
        $this->assertFalse(Schema::hasTable('subject'));
        $this->assertFalse(Schema::hasTable('transferrequest'));
        $this->assertFalse(Schema::hasTable('salaryrequest'));
        $this->assertFalse(Schema::hasTable('profileeditrequest'));
        $this->assertFalse(Schema::hasTable('users'));

        // 5. Assert columns were dropped
        $this->assertFalse(Schema::hasColumn('teacher', 'nameWithInitials'));
        $this->assertFalse(Schema::hasColumn('teacher', 'currentPosition'));
        $this->assertFalse(Schema::hasColumn('teacher', 'appointmentDate'));
        $this->assertFalse(Schema::hasColumn('teacher', 'spouseSchoolId'));
        $this->assertFalse(Schema::hasColumn('teacher', 'trainingStatus'));

        // 6. Assert active columns are untouched
        $this->assertTrue(Schema::hasColumn('teacher', 'fullName'));
        $this->assertTrue(Schema::hasColumn('teacher', 'position'));
        $this->assertTrue(Schema::hasColumn('teacher', 'firstappoint'));
    }
}
