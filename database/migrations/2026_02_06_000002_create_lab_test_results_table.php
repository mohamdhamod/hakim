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
        Schema::create('lab_test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('examination_id')->nullable()->constrained('examinations')->onDelete('set null');
            $table->foreignId('lab_test_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('ordered_by_user_id')->constrained('users')->onDelete('cascade'); // Doctor who ordered
            $table->date('test_date');
            $table->string('result_value')->nullable(); // Numeric or text result
            $table->text('result_text')->nullable(); // Additional notes
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->string('lab_name')->nullable(); // External lab name
            $table->string('lab_reference_number')->nullable();
            $table->string('attachment_path')->nullable(); // PDF/Image of lab result
            $table->enum('interpretation', ['normal', 'abnormal_low', 'abnormal_high', 'critical'])->nullable();
            $table->text('doctor_notes')->nullable();
            $table->timestamps();
            
            $table->index(['patient_id', 'test_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_test_results');
    }
};
