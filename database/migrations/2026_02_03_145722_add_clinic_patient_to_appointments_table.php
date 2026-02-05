<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds the link between appointments and clinic patient records.
     * - clinic_patient_id: Links to the patient record in the clinic's system
     * - is_new_patient: Flag to identify first-time patients
     * - linked_at: When the appointment was linked to a patient record
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Link to clinic's patient record (separate from user account)
            $table->foreignId('clinic_patient_id')
                ->nullable()
                ->after('patient_id')
                ->constrained('patients')
                ->onDelete('set null');
            
            // Flag for new patients (first visit)
            $table->boolean('is_new_patient')->default(true)->after('status');
            
            // When the appointment was linked to a patient record
            $table->timestamp('linked_at')->nullable()->after('cancelled_at');
            
            // Index for faster queries
            $table->index('clinic_patient_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['clinic_patient_id']);
            $table->dropColumn(['clinic_patient_id', 'is_new_patient', 'linked_at']);
        });
    }
};
