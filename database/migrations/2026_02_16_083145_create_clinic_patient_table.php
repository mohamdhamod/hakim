<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create the pivot table
        Schema::create('clinic_patient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['clinic_id', 'patient_id']);
            $table->index('patient_id');
        });

        // 2. Migrate existing data from patients.clinic_id to pivot table
        DB::statement('
            INSERT INTO clinic_patient (clinic_id, patient_id, created_at, updated_at)
            SELECT clinic_id, id, NOW(), NOW()
            FROM patients
            WHERE clinic_id IS NOT NULL
        ');

        // 3. Drop the clinic_id foreign key and column from patients table
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['clinic_id']);
            $table->dropIndex(['clinic_id', 'file_number']);
            $table->dropColumn('clinic_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add clinic_id to patients
        Schema::table('patients', function (Blueprint $table) {
            $table->foreignId('clinic_id')->nullable()->after('user_id')->constrained('clinics')->onDelete('cascade');
        });

        // Migrate data back (take the first clinic for each patient)
        DB::statement('
            UPDATE patients p
            JOIN (
                SELECT patient_id, MIN(clinic_id) as clinic_id
                FROM clinic_patient
                GROUP BY patient_id
            ) cp ON p.id = cp.patient_id
            SET p.clinic_id = cp.clinic_id
        ');

        // Re-add index
        Schema::table('patients', function (Blueprint $table) {
            $table->index(['clinic_id', 'file_number']);
        });

        Schema::dropIfExists('clinic_patient');
    }
};
