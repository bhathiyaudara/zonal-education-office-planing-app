<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('buildings')
            ->where('notes', 'like', 'Imported from Wilgamuwa%')
            ->orWhere('notes', 'like', 'Wilgamuwa Zone%')
            ->update(['notes' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
