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
        // Main vaccination types table
        Schema::create('vaccination_types', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Unique identifier
            $table->string('disease_prevented'); // Disease this vaccine prevents
            $table->integer('recommended_age_months')->nullable(); // Age in months
            $table->string('age_group')->nullable(); // Infant, Child, Adult, etc.
            $table->integer('doses_required')->default(1);
            $table->integer('interval_days')->nullable(); // Days between doses
            $table->integer('booster_after_months')->nullable();
            $table->boolean('is_mandatory')->default(false); // Required by law/WHO
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Vaccination type translations table
        Schema::create('vaccination_type_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vaccination_type_id')->constrained()->onDelete('cascade');
            $table->string('locale', 10)->index(); // ar, en, etc.
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['vaccination_type_id', 'locale']);
        });

        // Vaccination records table
        Schema::create('vaccination_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('vaccination_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('administered_by_user_id')->constrained('users')->onDelete('cascade');
            $table->date('vaccination_date');
            $table->integer('dose_number')->default(1);
            $table->string('batch_number')->nullable();
            $table->string('manufacturer')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('site')->nullable(); // Injection site: Left arm, Right thigh, etc.
            $table->text('reaction_notes')->nullable(); // Any adverse reactions
            $table->date('next_dose_due_date')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'missed', 'cancelled'])->default('completed');
            $table->timestamps();
            
            $table->index(['patient_id', 'vaccination_date']);
            $table->index('next_dose_due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccination_records');
        Schema::dropIfExists('vaccination_type_translations');
        Schema::dropIfExists('vaccination_types');
    }
};
