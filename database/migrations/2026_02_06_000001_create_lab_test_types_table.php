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
        Schema::create('lab_test_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('category'); // Hematology, Biochemistry, Microbiology, etc.
            $table->string('unit')->nullable(); // mg/dL, mmol/L, etc.
            $table->decimal('normal_range_min', 10, 2)->nullable();
            $table->decimal('normal_range_max', 10, 2)->nullable();
            $table->text('normal_range_text')->nullable(); // For non-numeric ranges
            $table->integer('order')->default(0); // Display order
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_test_types');
    }
};
