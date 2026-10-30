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
        if (!Schema::hasTable('buildings')) {
            Schema::create('buildings', function (Blueprint $table) {
                $table->id();
                $table->string('schoolId');
                $table->string('buildingName');
                $table->string('size')->nullable();
                $table->integer('floors')->default(1);
                $table->integer('constructionYear')->nullable();
                $table->integer('lastRepairYear')->nullable();
                $table->string('status')->nullable()->default('Good / Usable');
                $table->text('notes')->nullable();
                $table->timestamp('createdAt')->nullable();
                $table->timestamp('updatedAt')->nullable();

                $table->foreign('schoolId')->references('id')->on('school')->onDelete('cascade');
                $table->index('schoolId');
                $table->index('lastRepairYear');
            });
        }

        if (!Schema::hasTable('school_sanitation')) {
            Schema::create('school_sanitation', function (Blueprint $table) {
                $table->id();
                $table->string('schoolId')->unique();
                $table->integer('boysToilets')->default(0);
                $table->integer('girlsToilets')->default(0);
                $table->integer('teacherToilets')->default(0);
                $table->timestamp('createdAt')->nullable();
                $table->timestamp('updatedAt')->nullable();

                $table->foreign('schoolId')->references('id')->on('school')->onDelete('cascade');
            });
        }

        // Migrate Initial Buildings from Ofz_System
        $seedBuildings = [
            ['censusNo' => '04119', 'buildingName' => '1st hall', 'size' => '80*20', 'constructionYear' => 1999, 'lastRepairYear' => 2012, 'floors' => 1, 'status' => 'Needs Major Renovation'],
            ['censusNo' => '04119', 'buildingName' => '2nd Hall', 'size' => '100*50 - 2 floor', 'constructionYear' => 2000, 'lastRepairYear' => 2014, 'floors' => 2, 'status' => 'Needs Major Renovation'],
            ['censusNo' => '04119', 'buildingName' => 'commerce building', 'size' => '80*20', 'constructionYear' => 2005, 'lastRepairYear' => 2019, 'floors' => 1, 'status' => 'Good / Usable'],
            ['censusNo' => '04120', 'buildingName' => 'library', 'size' => '60*20', 'constructionYear' => 2010, 'lastRepairYear' => 2023, 'floors' => 1, 'status' => 'Good / Usable'],
            ['censusNo' => '04118', 'buildingName' => 'Main hall', 'size' => '100*40', 'constructionYear' => 2005, 'lastRepairYear' => 2013, 'floors' => 1, 'status' => 'Needs Major Renovation'],
            ['censusNo' => '04119', 'buildingName' => 'Art section', 'size' => '80*20', 'constructionYear' => 2001, 'lastRepairYear' => 2018, 'floors' => 1, 'status' => 'Good / Usable'],
            ['censusNo' => '04119', 'buildingName' => 'Main Hall', 'size' => '100*60', 'constructionYear' => 1995, 'lastRepairYear' => 2016, 'floors' => 1, 'status' => 'Minor Repairs Needed'],
        ];

        foreach ($seedBuildings as $b) {
            $school = DB::table('school')->where('censusNo', $b['censusNo'])->first();
            if ($school) {
                $exists = DB::table('buildings')
                    ->where('schoolId', $school->id)
                    ->where('buildingName', $b['buildingName'])
                    ->exists();

                if (!$exists) {
                    DB::table('buildings')->insert([
                        'schoolId' => $school->id,
                        'buildingName' => $b['buildingName'],
                        'size' => $b['size'],
                        'floors' => $b['floors'],
                        'constructionYear' => $b['constructionYear'],
                        'lastRepairYear' => $b['lastRepairYear'],
                        'status' => $b['status'],
                        'notes' => 'Imported from Ofz_System physical data records.',
                        'createdAt' => now(),
                        'updatedAt' => now(),
                    ]);
                }
            }
        }

        // Migrate Initial Sanitation / Toilet units for all 45 schools
        $seedToilets = [
            ['censusNo' => '04118', 'boys' => 4, 'girls' => 4, 'teachers' => 1],
            ['censusNo' => '04119', 'boys' => 7, 'girls' => 8, 'teachers' => 3],
            ['censusNo' => '04120', 'boys' => 6, 'girls' => 6, 'teachers' => 2],
            ['censusNo' => '04121', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04122', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04123', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04124', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04125', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04126', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04128', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04129', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04130', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04131', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04134', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04135', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04137', 'boys' => 2, 'girls' => 3, 'teachers' => 1],
            ['censusNo' => '04138', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04139', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04140', 'boys' => 4, 'girls' => 2, 'teachers' => 2],
            ['censusNo' => '04141', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04142', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04143', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04144', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04145', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04146', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04147', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04148', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04149', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04152', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04153', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04315', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04318', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04319', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04321', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04322', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04323', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04325', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04329', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04330', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04342', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04343', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04345', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04348', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04349', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
            ['censusNo' => '04350', 'boys' => 0, 'girls' => 0, 'teachers' => 0],
        ];

        foreach ($seedToilets as $t) {
            $school = DB::table('school')->where('censusNo', $t['censusNo'])->first();
            if ($school) {
                DB::table('school_sanitation')->updateOrInsert(
                    ['schoolId' => $school->id],
                    [
                        'boysToilets' => $t['boys'],
                        'girlsToilets' => $t['girls'],
                        'teacherToilets' => $t['teachers'],
                        'createdAt' => now(),
                        'updatedAt' => now(),
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_sanitation');
        Schema::dropIfExists('buildings');
    }
};
