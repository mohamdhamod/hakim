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
        Schema::table('lab_test_types', function (Blueprint $table) {
            // Add JSON column for age/gender-specific normal ranges
            $table->json('age_gender_ranges')->nullable()->after('normal_range_text');
            
            /* Structure example:
            {
                "male_adult": {"min": 13.5, "max": 17.5, "unit": "g/dL"},
                "female_adult": {"min": 12.0, "max": 15.5, "unit": "g/dL"},
                "male_child_0_1": {"min": 11.0, "max": 14.5, "unit": "g/dL"},
                "female_child_0_1": {"min": 11.0, "max": 14.5, "unit": "g/dL"},
                "male_child_1_5": {"min": 11.5, "max": 15.0, "unit": "g/dL"},
                "female_child_1_5": {"min": 11.5, "max": 15.0, "unit": "g/dL"},
                "male_child_5_12": {"min": 12.0, "max": 16.0, "unit": "g/dL"},
                "female_child_5_12": {"min": 12.0, "max": 16.0, "unit": "g/dL"},
                "male_teen_12_18": {"min": 13.0, "max": 17.0, "unit": "g/dL"},
                "female_teen_12_18": {"min": 12.0, "max": 15.5, "unit": "g/dL"}
            }
            */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_test_types', function (Blueprint $table) {
            $table->dropColumn('age_gender_ranges');
        });
    }
};
