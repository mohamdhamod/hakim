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
        Schema::create('config_title_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('config_title_id');
            $table->string('locale')->index();
            $table->unique(['config_title_id', 'locale']);
            $table->foreign('config_title_id')->references('id')->on('config_titles')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config_title_translations');
    }
};
