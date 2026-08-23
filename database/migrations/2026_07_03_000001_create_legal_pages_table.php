<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();
        $placeholder = '<p>This is placeholder text. Edit this policy from the admin panel under Legal.</p>';

        $defaults = [
            ['Privacy Policy', 'privacy-policy'],
            ['Terms of Service', 'terms-of-service'],
            ['Cookie Policy', 'cookie-policy'],
            ['Refund Policy', 'refund-policy'],
        ];

        foreach ($defaults as [$title, $slug]) {
            DB::table('legal_pages')->insert([
                'title'      => $title,
                'slug'       => $slug,
                'content'    => $placeholder,
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_pages');
    }
};
