<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add fields to teacher table
        Schema::table('teacher', function (Blueprint $table) {
            if (!Schema::hasColumn('teacher', 'census_no')) {
                $table->string('census_no', 20)->nullable()->after('schoolId');
            }
            if (!Schema::hasColumn('teacher', 'paysheet')) {
                $table->string('paysheet', 100)->nullable()->after('nic');
            }
            if (!Schema::hasColumn('teacher', 'title')) {
                $table->string('title', 50)->nullable()->after('paysheet');
            }
            if (!Schema::hasColumn('teacher', 'personal_file_no')) {
                $table->string('personal_file_no', 100)->nullable()->after('title');
            }
            if (!Schema::hasColumn('teacher', 'position')) {
                $table->string('position', 191)->nullable()->after('currentPosition');
            }
            if (!Schema::hasColumn('teacher', 'salaryby')) {
                $table->string('salaryby', 191)->nullable()->after('position');
            }
            if (!Schema::hasColumn('teacher', 'servicebased')) {
                $table->text('servicebased')->nullable()->after('salaryby');
            }
            if (!Schema::hasColumn('teacher', 'salarysource')) {
                $table->string('salarysource', 191)->nullable()->after('servicebased');
            }
            if (!Schema::hasColumn('teacher', 'ethnicity')) {
                $table->string('ethnicity', 50)->nullable()->after('gender');
            }
            if (!Schema::hasColumn('teacher', 'ethnicity_other')) {
                $table->string('ethnicity_other', 191)->nullable()->after('ethnicity');
            }
            if (!Schema::hasColumn('teacher', 'religion')) {
                $table->string('religion', 50)->nullable()->after('ethnicity_other');
            }
            if (!Schema::hasColumn('teacher', 'religion_other')) {
                $table->string('religion_other', 191)->nullable()->after('religion');
            }
            if (!Schema::hasColumn('teacher', 'marital_other')) {
                $table->string('marital_other', 191)->nullable()->after('maritalStatus');
            }
            if (!Schema::hasColumn('teacher', 'eduqua')) {
                $table->string('eduqua', 191)->nullable()->after('marital_other');
            }
            if (!Schema::hasColumn('teacher', 'subject_stream')) {
                $table->string('subject_stream', 191)->nullable()->after('eduqua');
            }
            if (!Schema::hasColumn('teacher', 'field_study')) {
                $table->string('field_study', 191)->nullable()->after('subject_stream');
            }
            if (!Schema::hasColumn('teacher', 'field_study_other')) {
                $table->string('field_study_other', 191)->nullable()->after('field_study');
            }
            if (!Schema::hasColumn('teacher', 'profqua')) {
                $table->string('profqua', 191)->nullable()->after('field_study_other');
            }
            if (!Schema::hasColumn('teacher', 'profqua_other')) {
                $table->string('profqua_other', 191)->nullable()->after('profqua');
            }
            if (!Schema::hasColumn('teacher', 'profall')) {
                $table->text('profall')->nullable()->after('profqua_other');
            }
            if (!Schema::hasColumn('teacher', 'service')) {
                $table->string('service', 191)->nullable()->after('profall');
            }
            if (!Schema::hasColumn('teacher', 'firstappoint')) {
                $table->date('firstappoint')->nullable()->after('service');
            }
            if (!Schema::hasColumn('teacher', 'class_grade')) {
                $table->string('class_grade', 100)->nullable()->after('firstappoint');
            }
            if (!Schema::hasColumn('teacher', 'clsappoint')) {
                $table->date('clsappoint')->nullable()->after('class_grade');
            }
            if (!Schema::hasColumn('teacher', 'schappoint')) {
                $table->date('schappoint')->nullable()->after('clsappoint');
            }
            if (!Schema::hasColumn('teacher', 'appt_section')) {
                $table->string('appt_section', 100)->nullable()->after('schappoint');
            }
            if (!Schema::hasColumn('teacher', 'appt_category')) {
                $table->string('appt_category', 100)->nullable()->after('appt_section');
            }
            if (!Schema::hasColumn('teacher', 'appt_code')) {
                $table->string('appt_code', 191)->nullable()->after('appt_category');
            }
            if (!Schema::hasColumn('teacher', 'appt_medium')) {
                $table->string('appt_medium', 50)->nullable()->after('appt_code');
            }
            if (!Schema::hasColumn('teacher', 'mainduty')) {
                $table->string('mainduty', 191)->nullable()->after('appt_medium');
            }
            if (!Schema::hasColumn('teacher', 'mainduty_other')) {
                $table->string('mainduty_other', 191)->nullable()->after('mainduty');
            }
            if (!Schema::hasColumn('teacher', 'secondduty')) {
                $table->string('secondduty', 191)->nullable()->after('mainduty_other');
            }
            if (!Schema::hasColumn('teacher', 'secondduty_other')) {
                $table->string('secondduty_other', 191)->nullable()->after('secondduty');
            }
            if (!Schema::hasColumn('teacher', 't1_section')) {
                $table->string('t1_section', 100)->nullable()->after('secondduty_other');
            }
            if (!Schema::hasColumn('teacher', 't1_category')) {
                $table->string('t1_category', 100)->nullable()->after('t1_section');
            }
            if (!Schema::hasColumn('teacher', 't1_code')) {
                $table->string('t1_code', 191)->nullable()->after('t1_category');
            }
            if (!Schema::hasColumn('teacher', 't1_medium')) {
                $table->string('t1_medium', 50)->nullable()->after('t1_code');
            }
            if (!Schema::hasColumn('teacher', 't1_periods')) {
                $table->integer('t1_periods')->nullable()->after('t1_medium');
            }
            if (!Schema::hasColumn('teacher', 't2_section')) {
                $table->string('t2_section', 100)->nullable()->after('t1_periods');
            }
            if (!Schema::hasColumn('teacher', 't2_category')) {
                $table->string('t2_category', 100)->nullable()->after('t2_section');
            }
            if (!Schema::hasColumn('teacher', 't2_code')) {
                $table->string('t2_code', 191)->nullable()->after('t2_category');
            }
            if (!Schema::hasColumn('teacher', 't2_medium')) {
                $table->string('t2_medium', 50)->nullable()->after('t2_code');
            }
            if (!Schema::hasColumn('teacher', 't2_periods')) {
                $table->integer('t2_periods')->nullable()->after('t2_medium');
            }
            if (!Schema::hasColumn('teacher', 'able1_section')) {
                $table->string('able1_section', 100)->nullable()->after('t2_periods');
            }
            if (!Schema::hasColumn('teacher', 'able1_category')) {
                $table->string('able1_category', 100)->nullable()->after('able1_section');
            }
            if (!Schema::hasColumn('teacher', 'able1_code')) {
                $table->string('able1_code', 191)->nullable()->after('able1_category');
            }
            if (!Schema::hasColumn('teacher', 'able1_medium')) {
                $table->string('able1_medium', 50)->nullable()->after('able1_code');
            }
            if (!Schema::hasColumn('teacher', 'able2_section')) {
                $table->string('able2_section', 100)->nullable()->after('able1_medium');
            }
            if (!Schema::hasColumn('teacher', 'able2_category')) {
                $table->string('able2_category', 100)->nullable()->after('able2_section');
            }
            if (!Schema::hasColumn('teacher', 'able2_code')) {
                $table->string('able2_code', 191)->nullable()->after('able2_category');
            }
            if (!Schema::hasColumn('teacher', 'able2_medium')) {
                $table->string('able2_medium', 50)->nullable()->after('able2_code');
            }
            if (!Schema::hasColumn('teacher', 'otherschool')) {
                $table->string('otherschool', 100)->nullable()->after('able2_medium');
            }
            if (!Schema::hasColumn('teacher', 'otherschool_census')) {
                $table->string('otherschool_census', 50)->nullable()->after('otherschool');
            }
            if (!Schema::hasColumn('teacher', 'otherschool_days')) {
                $table->string('otherschool_days', 255)->nullable()->after('otherschool_census');
            }
            if (!Schema::hasColumn('teacher', 'form_data')) {
                $table->json('form_data')->nullable()->after('otherschool_days');
            }
        });

        // 2. Create teacher_submissions table
        if (!Schema::hasTable('teacher_submissions')) {
            Schema::create('teacher_submissions', function (Blueprint $table) {
                $table->string('id', 36)->primary();
                $table->timestamp('created_at')->useCurrent();
                $table->string('census_no', 20);
                $table->string('school_name', 255);
                $table->string('nic', 20);
                $table->string('full_name', 255);
                $table->string('mobile', 50)->nullable();
                $table->json('data');
                $table->index('nic');
                $table->index('census_no');
            });
        }

        // 3. Populate schools from teacherOptions.json if not present
        $optionsPath = resource_path('data/teacherOptions.json');
        if (file_exists($optionsPath)) {
            $options = json_decode(file_get_contents($optionsPath), true);
            if (!empty($options['schools'])) {
                foreach ($options['schools'] as $s) {
                    $exists = DB::table('school')->where('censusNo', $s['censusNo'])->first();
                    if (!$exists) {
                        DB::table('school')->insert([
                            'id' => (string) Str::ulid(),
                            'name' => $s['name'],
                            'censusNo' => $s['censusNo'],
                            'category' => $s['info']['School Category'] ?? null,
                            'schoolType' => $s['info']['Approved School Type'] ?? null,
                            'eduZone' => $s['info']['Edu Zone_2026'] ?? 'Wilgamuwa',
                            'eduDivision' => $s['info']['Edu Division_2026'] ?? 'Wilgamuwa',
                            'sanctionedPositions' => isset($s['info']['2025 Staff Base']) ? (int)$s['info']['2025 Staff Base'] : null,
                            'isActive' => 1,
                            'createdAt' => now(),
                            'updatedAt' => now(),
                        ]);
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
        // Dropping columns if rolled back
        Schema::table('teacher', function (Blueprint $table) {
            $cols = [
                'census_no', 'paysheet', 'title', 'personal_file_no', 'position',
                'salaryby', 'servicebased', 'salarysource', 'ethnicity', 'ethnicity_other',
                'religion', 'religion_other', 'marital_other', 'eduqua', 'subject_stream',
                'field_study', 'field_study_other', 'profqua', 'profqua_other', 'profall',
                'service', 'firstappoint', 'class_grade', 'clsappoint', 'schappoint',
                'appt_section', 'appt_category', 'appt_code', 'appt_medium', 'mainduty',
                'mainduty_other', 'secondduty', 'secondduty_other', 't1_section', 't1_category',
                't1_code', 't1_medium', 't1_periods', 't2_section', 't2_category',
                't2_code', 't2_medium', 't2_periods', 'able1_section', 'able1_category',
                'able1_code', 'able1_medium', 'able2_section', 'able2_category', 'able2_code',
                'able2_medium', 'otherschool', 'otherschool_census', 'otherschool_days', 'form_data'
            ];
            foreach ($cols as $c) {
                if (Schema::hasColumn('teacher', $c)) {
                    $table->dropColumn($c);
                }
            }
        });

        Schema::dropIfExists('teacher_submissions');
    }
};
