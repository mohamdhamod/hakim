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
        Schema::create('examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->string('examination_number')->unique(); // رقم المعاينة
            $table->datetime('examination_date');
            
            // Chief Complaint - الشكوى الرئيسية
            $table->text('chief_complaint')->nullable();
            
            // History of Present Illness - تاريخ المرض الحالي
            $table->text('present_illness_history')->nullable();
            
            // Vital Signs - العلامات الحيوية
            $table->decimal('temperature', 4, 1)->nullable(); // درجة الحرارة
            $table->integer('blood_pressure_systolic')->nullable(); // ضغط الدم الانقباضي
            $table->integer('blood_pressure_diastolic')->nullable(); // ضغط الدم الانبساطي
            $table->integer('pulse_rate')->nullable(); // معدل النبض
            $table->integer('respiratory_rate')->nullable(); // معدل التنفس
            $table->decimal('weight', 5, 2)->nullable(); // الوزن
            $table->decimal('height', 5, 2)->nullable(); // الطول
            $table->integer('oxygen_saturation')->nullable(); // تشبع الأكسجين
            
            // Physical Examination - الفحص السريري
            $table->text('physical_examination')->nullable();
            
            // Diagnosis - التشخيص
            $table->text('diagnosis')->nullable();
            $table->string('icd_code')->nullable(); // كود التشخيص الدولي
            
            // Treatment Plan - خطة العلاج
            $table->text('treatment_plan')->nullable();
            
            // Prescriptions - الوصفات الطبية
            $table->text('prescriptions')->nullable();
            
            // Lab Tests - الفحوصات المخبرية
            $table->text('lab_tests_ordered')->nullable();
            $table->text('lab_tests_results')->nullable();
            
            // Imaging - الأشعة
            $table->text('imaging_ordered')->nullable();
            $table->text('imaging_results')->nullable();
            
            // Follow-up - المتابعة
            $table->date('follow_up_date')->nullable();
            $table->text('follow_up_notes')->nullable();
            
            // Doctor's Notes - ملاحظات الطبيب
            $table->text('doctor_notes')->nullable();
            
            // Status
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'examination_date']);
            $table->index(['user_id', 'examination_date']);
            $table->index(['clinic_id', 'examination_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examinations');
    }
};
