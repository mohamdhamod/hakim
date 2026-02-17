<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surgical_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('procedure_name');
            $table->date('procedure_date')->nullable();
            $table->string('hospital')->nullable();
            $table->string('surgeon')->nullable();
            $table->text('indication')->nullable(); // reason for surgery
            $table->text('complications')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('patient_id');
            $table->index('clinic_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surgical_histories');
    }
};
