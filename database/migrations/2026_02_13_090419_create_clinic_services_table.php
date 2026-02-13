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
        // Clinic services master table
        Schema::create('clinic_services', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // lab_tests, vaccinations, growth_chart, chronic_diseases
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Translations table for clinic services
        Schema::create('clinic_service_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_service_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unique(['clinic_service_id', 'locale']);
        });

        // Pivot table for clinic-service relationship
        Schema::create('clinic_clinic_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('clinic_service_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['clinic_id', 'clinic_service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_clinic_service');
        Schema::dropIfExists('clinic_service_translations');
        Schema::dropIfExists('clinic_services');
    }
};
