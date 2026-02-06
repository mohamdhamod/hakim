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
        Schema::create('growth_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('examination_id')->nullable()->constrained('examinations')->onDelete('set null');
            $table->foreignId('measured_by_user_id')->constrained('users')->onDelete('cascade');
            $table->date('measurement_date');
            $table->integer('age_months'); // Age at measurement in months
            $table->decimal('weight_kg', 5, 2)->nullable(); // Weight in kilograms
            $table->decimal('height_cm', 6, 2)->nullable(); // Height/Length in centimeters
            $table->decimal('head_circumference_cm', 5, 2)->nullable(); // For infants/young children
            $table->decimal('bmi', 5, 2)->nullable(); // Body Mass Index
            $table->decimal('weight_percentile', 5, 2)->nullable(); // WHO percentile
            $table->decimal('height_percentile', 5, 2)->nullable();
            $table->decimal('head_circumference_percentile', 5, 2)->nullable();
            $table->decimal('bmi_percentile', 5, 2)->nullable();
            $table->enum('interpretation', ['underweight', 'normal', 'overweight', 'obese'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['patient_id', 'measurement_date']);
            $table->index('age_months');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growth_measurements');
    }
};
