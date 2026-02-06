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
        Schema::create('chronic_disease_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('icd11_code')->nullable();
            $table->string('category'); // Diabetes, Hypertension, Asthma, etc.
            $table->text('management_guidelines_en')->nullable();
            $table->text('management_guidelines_ar')->nullable();
            $table->integer('followup_interval_days')->default(90); // Recommended follow-up
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('patient_chronic_diseases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('chronic_disease_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('diagnosed_by_user_id')->constrained('users')->onDelete('cascade');
            $table->date('diagnosis_date');
            $table->enum('severity', ['mild', 'moderate', 'severe'])->nullable();
            $table->enum('status', ['active', 'in_remission', 'resolved'])->default('active');
            $table->text('treatment_plan')->nullable();
            $table->text('notes')->nullable();
            $table->date('last_followup_date')->nullable();
            $table->date('next_followup_date')->nullable();
            $table->timestamps();
            
            $table->index(['patient_id', 'status']);
            $table->index('next_followup_date');
            $table->unique(['patient_id', 'chronic_disease_type_id']);
        });

        Schema::create('chronic_disease_monitoring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_chronic_disease_id')->constrained()->onDelete('cascade');
            $table->foreignId('examination_id')->nullable()->constrained('examinations')->onDelete('set null');
            $table->foreignId('recorded_by_user_id')->constrained('users')->onDelete('cascade');
            $table->date('monitoring_date');
            $table->string('parameter_name'); // Blood Sugar, Blood Pressure, Peak Flow, etc.
            $table->string('parameter_value');
            $table->string('parameter_unit')->nullable();
            $table->enum('status', ['controlled', 'uncontrolled', 'critical'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['patient_chronic_disease_id', 'monitoring_date']);
            $table->index('parameter_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chronic_disease_monitoring');
        Schema::dropIfExists('patient_chronic_diseases');
        Schema::dropIfExists('chronic_disease_types');
    }
};
