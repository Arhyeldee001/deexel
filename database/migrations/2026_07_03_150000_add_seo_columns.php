<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = ['services', 'projects', 'posts'];

    public function up(): void
    {
        foreach ($this->tables as $t) {
            Schema::table($t, function (Blueprint $table) use ($t) {
                if (! Schema::hasColumn($t, 'meta_keywords')) {
                    $table->string('meta_keywords')->nullable();
                }
                if (! Schema::hasColumn($t, 'og_image')) {
                    $table->string('og_image')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $t) {
            Schema::table($t, function (Blueprint $table) use ($t) {
                if (Schema::hasColumn($t, 'meta_keywords')) {
                    $table->dropColumn('meta_keywords');
                }
                if (Schema::hasColumn($t, 'og_image')) {
                    $table->dropColumn('og_image');
                }
            });
        }
    }
};
