<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_problems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title'); // problem name
            $table->string('icd_code')->nullable(); // ICD-10/11 code
            $table->date('onset_date')->nullable();
            $table->date('resolved_date')->nullable();
            $table->enum('status', ['active', 'resolved', 'inactive'])->default('active');
            $table->enum('severity', ['mild', 'moderate', 'severe'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('patient_id');
            $table->index('clinic_id');
            $table->index('status');
            $table->index('icd_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_problems');
    }
};
