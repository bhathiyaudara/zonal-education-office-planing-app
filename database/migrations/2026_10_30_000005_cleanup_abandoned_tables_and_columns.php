<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. Drop the 7 empty abandoned tables
        $tablesToDrop = [
            'teachersubject',
            'subjectstatistic',
            'subject',
            'transferrequest',
            'salaryrequest',
            'profileeditrequest',
            'users',
        ];

        foreach ($tablesToDrop as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();

        // 2. Drop foreign keys and indexes on `teacher` that might block column drops in MySQL
        if (DB::getDriverName() === 'mysql') {
            try {
                DB::statement('ALTER TABLE `teacher` DROP FOREIGN KEY `fk_teacher_spouseSchoolId`');
            } catch (\Throwable $e) {}

            try {
                DB::statement('ALTER TABLE `teacher` DROP INDEX `idx_teacher_spouseSchoolId`');
            } catch (\Throwable $e) {}

            try {
                DB::statement('ALTER TABLE `teacher` DROP INDEX `idx_teacher_nameWithInitials`');
            } catch (\Throwable $e) {}

            try {
                DB::statement('ALTER TABLE `teacher` DROP INDEX `idx_teacher_serviceGrade`');
            } catch (\Throwable $e) {}
        }

        // 3. Drop the 18 empty old columns and 11 legacy duplicate columns from `teacher`
        if (Schema::hasTable('teacher')) {
            $columnsToDrop = [
                // 18 empty abandoned columns
                'spouseIsTeacherInWilgamuwa',
                'spouseNameWithInitials',
                'spouseNic',
                'spouseSchoolId',
                'undergraduateDegrees',
                'undergraduateDegreesOther',
                'postgraduateDiplomas',
                'postgraduateDiplomasOther',
                'postgraduateDegrees',
                'postgraduateDegreesOther',
                'additionalTeachingQualifications',
                'pastServiceDetails',
                'appointmentLetterUrl',
                'isLockedByZonal',
                'isLockedBySchool',
                'trainingStatus',
                'natureOfAppointment',
                'salaryPayingSchool',

                // 11 legacy duplicate columns
                'nameWithInitials',
                'currentPosition',
                'appointmentDate',
                'serviceGrade',
                'serviceGradeAppointedDate',
                'joinedCurrentSchoolDate',
                'fileNumber',
                'salaryNumber',
                'appointmentMedium',
                'appointmentType',
                'appointmentSubject',
            ];

            foreach ($columnsToDrop as $col) {
                if (Schema::hasColumn('teacher', $col)) {
                    try {
                        Schema::table('teacher', function (Blueprint $table) use ($col) {
                            $table->dropColumn($col);
                        });
                    } catch (\Throwable $e) {
                        // Ignore if already dropped
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Abandoned legacy tables and columns are not restored automatically.
    }
};
