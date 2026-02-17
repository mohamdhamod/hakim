<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Social History
            $table->string('smoking_status')->nullable()->after('notes'); // never, former, current
            $table->string('alcohol_status')->nullable()->after('smoking_status'); // never, occasional, regular
            $table->string('occupation')->nullable()->after('alcohol_status');
            $table->string('marital_status')->nullable()->after('occupation'); // single, married, divorced, widowed
            $table->text('lifestyle_notes')->nullable()->after('marital_status');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'smoking_status', 'alcohol_status', 'occupation',
                'marital_status', 'lifestyle_notes',
            ]);
        });
    }
};
