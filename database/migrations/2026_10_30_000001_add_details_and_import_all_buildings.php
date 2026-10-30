<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add new attributes to buildings table
        if (Schema::hasTable('buildings')) {
            Schema::table('buildings', function (Blueprint $table) {
                if (!Schema::hasColumn('buildings', 'buildingType')) {
                    $table->string('buildingType', 100)->default('Learning / ඉගෙනුම්')->after('buildingName');
                }
                if (!Schema::hasColumn('buildings', 'lengthFeet')) {
                    $table->decimal('lengthFeet', 8, 2)->nullable()->after('size');
                }
                if (!Schema::hasColumn('buildings', 'widthFeet')) {
                    $table->decimal('widthFeet', 8, 2)->nullable()->after('lengthFeet');
                }
                if (!Schema::hasColumn('buildings', 'areaSqft')) {
                    $table->decimal('areaSqft', 10, 2)->nullable()->after('widthFeet');
                }
            });
        }

        // 2. Add norm requirements to school_sanitation table
        if (Schema::hasTable('school_sanitation')) {
            Schema::table('school_sanitation', function (Blueprint $table) {
                if (!Schema::hasColumn('school_sanitation', 'reqBoysToilets')) {
                    $table->integer('reqBoysToilets')->default(0)->after('schoolId');
                }
                if (!Schema::hasColumn('school_sanitation', 'reqGirlsToilets')) {
                    $table->integer('reqGirlsToilets')->default(0)->after('reqBoysToilets');
                }
                if (!Schema::hasColumn('school_sanitation', 'reqTeacherToilets')) {
                    $table->integer('reqTeacherToilets')->default(0)->after('reqGirlsToilets');
                }
            });
        }

        // 3. Import full 226 real buildings from Wilgamuwa Education Zone
        $buildingsFile = resource_path('data/buildings.json');
        if (file_exists($buildingsFile)) {
            $buildingRecords = json_decode(file_get_contents($buildingsFile), true);
            if (!empty($buildingRecords)) {
                // Remove the 7 initial placeholder records if present
                $currentCount = DB::table('buildings')->count();
                if ($currentCount <= 10) {
                    DB::table('buildings')->truncate();
                }

                $now = now();
                foreach ($buildingRecords as $b) {
                    $school = DB::table('school')->where('censusNo', $b['censusNo'])->first();
                    if ($school) {
                        // Check if building already exists for this school
                        $exists = DB::table('buildings')
                            ->where('schoolId', $school->id)
                            ->where('buildingName', $b['buildingName'])
                            ->exists();

                        if (!$exists) {
                            DB::table('buildings')->insert([
                                'schoolId' => $school->id,
                                'buildingName' => $b['buildingName'],
                                'buildingType' => $b['buildingType'] ?? 'Learning / ඉගෙනුම්',
                                'size' => $b['size'] ?? null,
                                'lengthFeet' => $b['lengthFeet'] ?? null,
                                'widthFeet' => $b['widthFeet'] ?? null,
                                'areaSqft' => $b['areaSqft'] ?? null,
                                'floors' => $b['floors'] ?? 1,
                                'constructionYear' => $b['constructionYear'] ?? null,
                                'lastRepairYear' => $b['lastRepairYear'] ?? null,
                                'status' => $b['status'] ?? 'Good / Usable',
                                'notes' => $b['notes'] ?? null,
                                'createdAt' => $now,
                                'updatedAt' => $now,
                            ]);
                        }
                    }
                }
            }
        }

        // 4. Update all 45 schools with accurate Sanitation & Norm requirements
        $sanitationFile = resource_path('data/sanitation.json');
        if (file_exists($sanitationFile)) {
            $sanitationRecords = json_decode(file_get_contents($sanitationFile), true);
            if (!empty($sanitationRecords)) {
                $now = now();
                foreach ($sanitationRecords as $s) {
                    $school = DB::table('school')->where('censusNo', $s['censusNo'])->first();
                    if ($school) {
                        DB::table('school_sanitation')->updateOrInsert(
                            ['schoolId' => $school->id],
                            [
                                'reqBoysToilets' => (int)($s['reqBoys'] ?? 0),
                                'reqGirlsToilets' => (int)($s['reqGirls'] ?? 0),
                                'reqTeacherToilets' => (int)($s['reqTeachers'] ?? 0),
                                'boysToilets' => (int)($s['availBoys'] ?? 0),
                                'girlsToilets' => (int)($s['availGirls'] ?? 0),
                                'teacherToilets' => (int)($s['availTeachers'] ?? 0),
                                'updatedAt' => $now,
                            ]
                        );
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
        if (Schema::hasTable('buildings')) {
            Schema::table('buildings', function (Blueprint $table) {
                if (Schema::hasColumn('buildings', 'buildingType')) {
                    $table->dropColumn('buildingType');
                }
                if (Schema::hasColumn('buildings', 'lengthFeet')) {
                    $table->dropColumn('lengthFeet');
                }
                if (Schema::hasColumn('buildings', 'widthFeet')) {
                    $table->dropColumn('widthFeet');
                }
                if (Schema::hasColumn('buildings', 'areaSqft')) {
                    $table->dropColumn('areaSqft');
                }
            });
        }

        if (Schema::hasTable('school_sanitation')) {
            Schema::table('school_sanitation', function (Blueprint $table) {
                if (Schema::hasColumn('school_sanitation', 'reqBoysToilets')) {
                    $table->dropColumn('reqBoysToilets');
                }
                if (Schema::hasColumn('school_sanitation', 'reqGirlsToilets')) {
                    $table->dropColumn('reqGirlsToilets');
                }
                if (Schema::hasColumn('school_sanitation', 'reqTeacherToilets')) {
                    $table->dropColumn('reqTeacherToilets');
                }
            });
        }
    }
};
