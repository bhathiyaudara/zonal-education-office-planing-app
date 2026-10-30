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
        if (Schema::hasTable('school')) {
            Schema::table('school', function (Blueprint $table) {
                if (!Schema::hasColumn('school', 'landArea')) {
                    $table->string('landArea', 191)->nullable()->after('address');
                }
                if (!Schema::hasColumn('school', 'difficultyCategory')) {
                    $table->string('difficultyCategory', 100)->nullable()->after('landArea');
                }
                if (!Schema::hasColumn('school', 'dsDivision')) {
                    $table->string('dsDivision', 191)->nullable()->after('eduDivision');
                }
                if (!Schema::hasColumn('school', 'gnDivision')) {
                    $table->string('gnDivision', 191)->nullable()->after('dsDivision');
                }
                if (!Schema::hasColumn('school', 'latitude')) {
                    $table->decimal('latitude', 10, 7)->nullable()->after('gnDivision');
                }
                if (!Schema::hasColumn('school', 'longitude')) {
                    $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
                }
            });

            // Set initial dsDivision based on existing eduDivision where dsDivision is null
            DB::table('school')->whereNull('dsDivision')->where('eduDivision', 'Wilgamuwa')->update([
                'dsDivision' => 'Wilgamuwa',
            ]);
            DB::table('school')->whereNull('dsDivision')->where('eduDivision', 'Laggala')->update([
                'dsDivision' => 'Laggala-Pallegama',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('school')) {
            Schema::table('school', function (Blueprint $table) {
                $columns = ['landArea', 'difficultyCategory', 'dsDivision', 'gnDivision', 'latitude', 'longitude'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('school', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
