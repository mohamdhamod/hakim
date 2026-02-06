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
        // Main chronic disease types table
        Schema::create('chronic_disease_types', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Unique identifier
            $table->string('icd11_code')->nullable();
            $table->string('category'); // Diabetes, Hypertension, Asthma, etc.
            $table->integer('followup_interval_days')->default(90); // Recommended follow-up
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Chronic disease type translations table
        Schema::create('chronic_disease_type_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chronic_disease_type_id')
                ->constrained('chronic_disease_types')
                ->onDelete('cascade')
                ->name('cd_type_translations_type_id_fk');
            $table->string('locale', 10)->index(); // ar, en, etc.
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('management_guidelines')->nullable();
            $table->timestamps();
            
            $table->unique(['chronic_disease_type_id', 'locale'], 'cd_type_locale_unique');
        });

        Schema::create('patient_chronic_diseases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('chronic_disease_type_id')
                ->constrained('chronic_disease_types')
                ->onDelete('cascade')
                ->name('pcd_cd_type_id_fk');
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
            $table->unique(['patient_id', 'chronic_disease_type_id'], 'pcd_patient_type_unique');
        });

        Schema::create('chronic_disease_monitoring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_chronic_disease_id')
                ->constrained('patient_chronic_diseases')
                ->onDelete('cascade')
                ->name('cdm_pcd_id_fk');
            $table->foreignId('examination_id')->nullable()->constrained('examinations')->onDelete('set null');
            $table->foreignId('recorded_by_user_id')->constrained('users')->onDelete('cascade');
            $table->date('monitoring_date');
            $table->string('parameter_name'); // Blood Sugar, Blood Pressure, Peak Flow, etc.
            $table->string('parameter_value');
            $table->string('parameter_unit')->nullable();
            $table->enum('status', ['controlled', 'uncontrolled', 'critical'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['patient_chronic_disease_id', 'monitoring_date'], 'cdm_pcd_date_idx');
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
        Schema::dropIfExists('chronic_disease_type_translations');
        Schema::dropIfExists('chronic_disease_types');
    }
};
