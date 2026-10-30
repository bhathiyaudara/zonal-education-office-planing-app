<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create subject_statistic table if not exists
        if (!Schema::hasTable('subject_statistic')) {
            Schema::create('subject_statistic', function (Blueprint $table) {
                $table->string('id', 36)->primary();
                $table->string('schoolId', 36)->nullable()->index();
                $table->string('schoolCensusNo', 20)->nullable()->index();
                $table->string('schoolName', 191)->nullable();
                $table->string('academicYear', 20)->default('2025')->index();
                $table->string('level', 20)->index(); // 'OL', 'AL', 'STREAM'
                $table->string('grade', 20)->index(); // 'Grade 10', 'Grade 11', 'Grade 12', 'Grade 13'
                $table->string('subject', 191)->index();
                $table->integer('maleCount')->default(0);
                $table->integer('femaleCount')->default(0);
                $table->integer('totalCount')->default(0);
                $table->timestamp('createdAt')->nullable();
                $table->timestamp('updatedAt')->nullable();
            });
        }

        // 2. Create pirivena table if not exists
        if (!Schema::hasTable('pirivena')) {
            Schema::create('pirivena', function (Blueprint $table) {
                $table->string('id', 36)->primary();
                $table->string('code', 50)->unique();
                $table->string('name', 191);
                $table->integer('teachersCount')->default(0);
                $table->integer('layCount')->default(0);
                $table->integer('monkCount')->default(0);
                $table->integer('totalStudents')->default(0);
                $table->string('academicYear', 20)->default('2025');
                $table->timestamp('createdAt')->nullable();
                $table->timestamp('updatedAt')->nullable();
            });
        }

        $now = now();

        // 3. Populate studentcount table with 2025 (and 2026) census data
        $censusPath = database_path('data/students_census_2025.json');
        if (File::exists($censusPath)) {
            $schoolsCensus = json_decode(File::get($censusPath), true) ?: [];

            // Cache existing schools by censusNo and name
            $dbSchools = DB::table('school')->get(['id', 'censusNo', 'name'])->keyBy('censusNo');

            foreach ($schoolsCensus as $sc) {
                $censusNo = $sc['census_no'] ?? '';
                $school = $dbSchools->get($censusNo);
                if (!$school) {
                    continue;
                }

                $grades = $sc['grades'] ?? [];
                foreach ($grades as $gradeName => $gData) {
                    $m = (int)($gData['male'] ?? 0);
                    $f = (int)($gData['female'] ?? 0);
                    $clsCount = (int)($gData['classes'] ?? 0);
                    $tot = (int)($gData['total'] ?? ($m + $f));

                    // Build class breakdown
                    $classNames = [];
                    $classDetails = [];
                    if ($clsCount <= 1) {
                        $classNames = ['A'];
                        $classDetails = [
                            ['name' => 'A', 'maleCount' => $m, 'femaleCount' => $f]
                        ];
                    } else {
                        $letters = range('A', 'Z');
                        $mPerClass = intdiv($m, $clsCount);
                        $mRem = $m % $clsCount;
                        $fPerClass = intdiv($f, $clsCount);
                        $fRem = $f % $clsCount;

                        for ($i = 0; $i < $clsCount; $i++) {
                            $cLetter = $letters[$i] ?? ('C' . ($i + 1));
                            $classNames[] = $cLetter;
                            $classDetails[] = [
                                'name' => $cLetter,
                                'maleCount' => $mPerClass + ($i < $mRem ? 1 : 0),
                                'femaleCount' => $fPerClass + ($i < $fRem ? 1 : 0),
                            ];
                        }
                    }

                    $classesStr = implode(', ', $classNames);
                    $detailsJson = json_encode($classDetails);

                    // Seed for both 2025 and 2026
                    foreach (['2025', '2026'] as $year) {
                        $existing = DB::table('studentcount')
                            ->where('schoolId', $school->id)
                            ->where('grade', $gradeName)
                            ->where('academicYear', $year)
                            ->first();

                        if ($existing) {
                            DB::table('studentcount')
                                ->where('id', $existing->id)
                                ->update([
                                    'maleCount' => $m,
                                    'femaleCount' => $f,
                                    'classCount' => $clsCount,
                                    'classes' => $classesStr,
                                    'classDetails' => $detailsJson,
                                    'updatedAt' => $now,
                                ]);
                        } else {
                            DB::table('studentcount')->insert([
                                'id' => (string) Str::ulid(),
                                'schoolId' => $school->id,
                                'grade' => $gradeName,
                                'academicYear' => $year,
                                'maleCount' => $m,
                                'femaleCount' => $f,
                                'classCount' => $clsCount,
                                'classes' => $classesStr,
                                'classDetails' => $detailsJson,
                                'createdAt' => $now,
                                'updatedAt' => $now,
                            ]);
                        }
                    }
                }
            }
        }

        // Map school name prefixes from OL/AL sheets to censusNo
        $schoolNameToCensus = [
            'HATTOTA AMUNA' => '04143',
            'MAHALAKOTUWA' => '04152',
            'MINIPURA VIJAYA' => '04118',
            'PALLEGAMA' => '04120',
            'MA OYA' => '04349',
            'AGRABODHI' => '04129',
            'PUWAKPITIYA' => '04153',
            'RANAMURE' => '04125',
            'NAMINIOYA' => '04119',
            'NUGAGOLLA' => '04123',
            'MARAKA' => '04121',
            'HANDUNGAMUWA' => '04122',
            'KEKALATHANNA' => '04144',
            'MEDAKANDA' => '04124',
            'WEHERAGALAYAYA' => '04319',
            'SONUTHTHARA' => '04126',
            'GURUWELAYAYA' => '04149',
        ];

        $schoolsByCensus = DB::table('school')->get(['id', 'censusNo', 'name'])->keyBy('censusNo');

        // Helper to find school
        $resolveSchool = function ($name) use ($schoolNameToCensus, $schoolsByCensus) {
            foreach ($schoolNameToCensus as $key => $cNo) {
                if (stripos($name, $key) !== false && $schoolsByCensus->has($cNo)) {
                    return $schoolsByCensus->get($cNo);
                }
            }
            return null;
        };

        // 4. Populate OL subject statistics
        $olPath = database_path('data/ol_subjects_2025.json');
        if (File::exists($olPath)) {
            $olSubjects = json_decode(File::get($olPath), true) ?: [];
            foreach ($olSubjects as $row) {
                $subj = $row['subject'] ?? '';
                if (!$subj || $subj === 'Total') continue;

                foreach ($row['schools'] ?? [] as $sName => $data) {
                    $school = $resolveSchool($sName);
                    foreach (['grade_10' => 'Grade 10', 'grade_11' => 'Grade 11'] as $k => $grName) {
                        $m = (int)($data[$k]['male'] ?? 0);
                        $f = (int)($data[$k]['female'] ?? 0);
                        $t = (int)($data[$k]['total'] ?? ($m + $f));

                        DB::table('subject_statistic')->insert([
                            'id' => (string) Str::ulid(),
                            'schoolId' => $school?->id,
                            'schoolCensusNo' => $school?->censusNo,
                            'schoolName' => $school?->name ?? $sName,
                            'academicYear' => '2025',
                            'level' => 'OL',
                            'grade' => $grName,
                            'subject' => $subj,
                            'maleCount' => $m,
                            'femaleCount' => $f,
                            'totalCount' => $t,
                            'createdAt' => $now,
                            'updatedAt' => $now,
                        ]);
                    }
                }
            }
        }

        // 5. Populate AL subject statistics
        $alPath = database_path('data/al_subjects_2025.json');
        if (File::exists($alPath)) {
            $alSubjects = json_decode(File::get($alPath), true) ?: [];
            foreach ($alSubjects as $row) {
                $subj = $row['subject'] ?? '';
                if (!$subj || $subj === 'Total') continue;

                foreach ($row['schools'] ?? [] as $sName => $data) {
                    $school = $resolveSchool($sName);
                    foreach (['grade_12' => 'Grade 12', 'grade_13' => 'Grade 13'] as $k => $grName) {
                        $m = (int)($data[$k]['male'] ?? 0);
                        $f = (int)($data[$k]['female'] ?? 0);
                        $t = (int)($data[$k]['total'] ?? ($m + $f));

                        DB::table('subject_statistic')->insert([
                            'id' => (string) Str::ulid(),
                            'schoolId' => $school?->id,
                            'schoolCensusNo' => $school?->censusNo,
                            'schoolName' => $school?->name ?? $sName,
                            'academicYear' => '2025',
                            'level' => 'AL',
                            'grade' => $grName,
                            'subject' => $subj,
                            'maleCount' => $m,
                            'femaleCount' => $f,
                            'totalCount' => $t,
                            'createdAt' => $now,
                            'updatedAt' => $now,
                        ]);
                    }
                }
            }
        }

        // 6. Populate AL stream statistics
        $streamPath = database_path('data/al_streams_2025.json');
        if (File::exists($streamPath)) {
            $streams = json_decode(File::get($streamPath), true) ?: [];
            foreach ($streams as $row) {
                $stream = $row['stream'] ?? '';
                if (!$stream || $stream === 'Total') continue;

                foreach ($row['schools'] ?? [] as $sName => $data) {
                    $school = $resolveSchool($sName);
                    foreach (['grade_12' => 'Grade 12', 'grade_13' => 'Grade 13'] as $k => $grName) {
                        $m = (int)($data[$k]['male'] ?? 0);
                        $f = (int)($data[$k]['female'] ?? 0);
                        $t = (int)($data[$k]['total'] ?? ($m + $f));

                        DB::table('subject_statistic')->insert([
                            'id' => (string) Str::ulid(),
                            'schoolId' => $school?->id,
                            'schoolCensusNo' => $school?->censusNo,
                            'schoolName' => $school?->name ?? $sName,
                            'academicYear' => '2025',
                            'level' => 'STREAM',
                            'grade' => $grName,
                            'subject' => $stream,
                            'maleCount' => $m,
                            'femaleCount' => $f,
                            'totalCount' => $t,
                            'createdAt' => $now,
                            'updatedAt' => $now,
                        ]);
                    }
                }
            }
        }

        // 7. Populate Pirivena table
        $pirivenaPath = database_path('data/pirivena_census_2025.json');
        if (File::exists($pirivenaPath)) {
            $pirivenas = json_decode(File::get($pirivenaPath), true) ?: [];
            foreach ($pirivenas as $pv) {
                $code = $pv['code'] ?? '';
                if (!$code) continue;

                DB::table('pirivena')->updateOrInsert(
                    ['code' => $code],
                    [
                        'id' => (string) Str::ulid(),
                        'name' => $pv['name'] ?? '',
                        'teachersCount' => (int)($pv['teachers'] ?? 0),
                        'layCount' => (int)($pv['lay'] ?? 0),
                        'monkCount' => (int)($pv['monk'] ?? 0),
                        'totalStudents' => (int)($pv['total'] ?? 0),
                        'academicYear' => '2025',
                        'createdAt' => $now,
                        'updatedAt' => $now,
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_statistic');
        Schema::dropIfExists('pirivena');
    }
};
