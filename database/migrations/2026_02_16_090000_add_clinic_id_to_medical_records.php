<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'vaccination_records',
            'lab_test_results',
            'growth_measurements',
            'patient_chronic_diseases',
            'chronic_disease_monitoring',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->foreignId('clinic_id')->nullable()->after('id')->constrained('clinics')->nullOnDelete();
            });
        }

        // Populate existing records with clinic_id from clinic_patient pivot
        // For vaccination_records, lab_test_results, growth_measurements, patient_chronic_diseases:
        // They have patient_id → use clinic_patient to find the first clinic
        $patientTables = [
            'vaccination_records' => 'patient_id',
            'lab_test_results' => 'patient_id',
            'growth_measurements' => 'patient_id',
            'patient_chronic_diseases' => 'patient_id',
        ];

        foreach ($patientTables as $table => $patientColumn) {
            DB::statement("
                UPDATE {$table}
                SET clinic_id = (
                    SELECT cp.clinic_id
                    FROM clinic_patient cp
                    WHERE cp.patient_id = {$table}.{$patientColumn}
                    LIMIT 1
                )
                WHERE clinic_id IS NULL
            ");
        }

        // For chronic_disease_monitoring: it links to patient_chronic_diseases → patient_id
        DB::statement("
            UPDATE chronic_disease_monitoring
            SET clinic_id = (
                SELECT cp.clinic_id
                FROM patient_chronic_diseases pcd
                JOIN clinic_patient cp ON cp.patient_id = pcd.patient_id
                WHERE pcd.id = chronic_disease_monitoring.patient_chronic_disease_id
                LIMIT 1
            )
            WHERE clinic_id IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'vaccination_records',
            'lab_test_results',
            'growth_measurements',
            'patient_chronic_diseases',
            'chronic_disease_monitoring',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                $blueprint->dropForeign([$table . '_clinic_id_foreign'] ?? []);
                $blueprint->dropColumn('clinic_id');
            });
        }
    }
};
