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
        // Main table
        Schema::create('lab_test_types', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Unique identifier
            $table->string('category'); // Hematology, Biochemistry, Microbiology, etc.
            $table->string('unit')->nullable(); // mg/dL, mmol/L, etc.
            $table->decimal('normal_range_min', 10, 2)->nullable();
            $table->decimal('normal_range_max', 10, 2)->nullable();
            $table->text('normal_range_text')->nullable(); // For non-numeric ranges
            $table->integer('order')->default(0); // Display order
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Translations table
        Schema::create('lab_test_type_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_test_type_id')->constrained()->onDelete('cascade');
            $table->string('locale', 10)->index(); // ar, en, etc.
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['lab_test_type_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_test_type_translations');
        Schema::dropIfExists('lab_test_types');
    }
};
