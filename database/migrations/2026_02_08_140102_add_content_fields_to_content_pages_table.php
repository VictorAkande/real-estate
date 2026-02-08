<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('content_pages', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('title');
            $table->string('cta_text')->nullable()->after('body');
            $table->string('cta_link')->nullable()->after('cta_text');
            $table->string('image_path')->nullable()->after('cta_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_pages', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'cta_text', 'cta_link', 'image_path']);
        });
    }
};
