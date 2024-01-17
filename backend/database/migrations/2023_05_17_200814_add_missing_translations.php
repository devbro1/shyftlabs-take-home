<?php

use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Change Requests', 'translation' => 'Change Requests']);
        DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Batch Jobs', 'translation' => 'Batch Jobs']);
        // DB::table('translations')->insert(['language' => 'en', 'namespace' => 'leftmenu', 'key' => 'Announcements', 'translation' => 'Announcements']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
