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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('listing_type', ['sale', 'rent', 'shortlet']);
            $table->string('property_type');
            $table->decimal('price', 15, 2)->default(0);
            $table->unsignedTinyInteger('bedrooms')->nullable();
            $table->unsignedTinyInteger('bathrooms')->nullable();
            $table->unsignedTinyInteger('toilets')->nullable();
            $table->unsignedTinyInteger('parking_spaces')->nullable();
            $table->decimal('area_sqm', 10, 2)->nullable();
            $table->string('address');
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('active');
            $table->boolean('featured')->default(false);
            $table->string('cover_image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['listing_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
