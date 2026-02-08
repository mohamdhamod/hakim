<?php

namespace App\Services;

/**
 * WHO Growth Charts Calculator Service
 * Calculates percentiles and Z-scores based on WHO Child Growth Standards
 * 
 * @link https://www.who.int/tools/child-growth-standards
 */
class WhoGrowthCalculator
{
    /**
     * Calculate BMI percentile for age
     */
    public function calculateBmiPercentile($ageMonths, $gender, $bmi)
    {
        // WHO standards for BMI-for-age (0-5 years = 0-60 months)
        // This is a simplified calculation - real implementation would use WHO LMS tables
        
        if ($ageMonths > 228) { // > 19 years
            return null; // WHO charts only go to 19 years
        }

        // BMI classification for adults
        if ($ageMonths >= 228) { // 19+ years
            if ($bmi < 18.5) return 5;
            if ($bmi < 25) return 50;
            if ($bmi < 30) return 85;
            return 95;
        }

        // Simplified percentile estimation for children
        // In production, use WHO LMS (Lambda-Mu-Sigma) tables
        $referenceData = $this->getBmiReferenceData($ageMonths, $gender);
        
        return $this->calculatePercentileFromZScore(
            $this->calculateZScore($bmi, $referenceData)
        );
    }

    /**
     * Calculate weight percentile for age
     */
    public function calculateWeightPercentile($ageMonths, $gender, $weightKg)
    {
        if ($ageMonths > 120) { // > 10 years
            return null; // Weight-for-age only recommended until 10 years
        }

        $referenceData = $this->getWeightReferenceData($ageMonths, $gender);
        
        return $this->calculatePercentileFromZScore(
            $this->calculateZScore($weightKg, $referenceData)
        );
    }

    /**
     * Calculate height percentile for age
     */
    public function calculateHeightPercentile($ageMonths, $gender, $heightCm)
    {
        if ($ageMonths > 228) { // > 19 years
            return null;
        }

        $referenceData = $this->getHeightReferenceData($ageMonths, $gender);
        
        return $this->calculatePercentileFromZScore(
            $this->calculateZScore($heightCm, $referenceData)
        );
    }

    /**
     * Calculate head circumference percentile
     */
    public function calculateHeadCircumferencePercentile($ageMonths, $gender, $headCm)
    {
        if ($ageMonths > 60) { // > 5 years
            return null; // Head circumference mainly for 0-5 years
        }

        $referenceData = $this->getHeadCircumferenceReferenceData($ageMonths, $gender);
        
        return $this->calculatePercentileFromZScore(
            $this->calculateZScore($headCm, $referenceData)
        );
    }

    /**
     * Get interpretation of percentile
     */
    public function getInterpretation($percentile, $metric)
    {
        if ($percentile === null) {
            return 'not_applicable';
        }

        // WHO classifications
        if ($percentile < 3) {
            return 'severely_low'; // Below -2 SD
        } elseif ($percentile < 15) {
            return 'low'; // Below -1 SD
        } elseif ($percentile <= 85) {
            return 'normal';
        } elseif ($percentile <= 97) {
            return 'high'; // Above +1 SD
        } else {
            return 'severely_high'; // Above +2 SD
        }
    }

    /**
     * Calculate Z-score using LMS method
     * Z = [(value/M)^L - 1] / (L*S)
     */
    private function calculateZScore($value, $referenceData)
    {
        $L = $referenceData['L'];
        $M = $referenceData['M'];
        $S = $referenceData['S'];

        if ($L == 0) {
            // Box-Cox transformation when L=0
            return log($value / $M) / $S;
        }

        return (pow($value / $M, $L) - 1) / ($L * $S);
    }

    /**
     * Convert Z-score to percentile
     */
    private function calculatePercentileFromZScore($zScore)
    {
        // Using standard normal distribution approximation
        // This is simplified - use a proper statistical library in production
        
        if ($zScore < -3) return 0.1;
        if ($zScore < -2) return 2.3;
        if ($zScore < -1) return 15.9;
        if ($zScore < 0) return 50 - (abs($zScore) * 34);
        if ($zScore < 1) return 50 + ($zScore * 34);
        if ($zScore < 2) return 84.1;
        if ($zScore < 3) return 97.7;
        return 99.9;
    }

    /**
     * Get BMI reference data (simplified)
     * Real implementation should load from WHO LMS tables
     */
    private function getBmiReferenceData($ageMonths, $gender)
    {
        // Simplified example for 24 months old male
        // In production, load from comprehensive WHO LMS tables
        
        $isMale = strtolower($gender) === 'male';
        
        // Example data - interpolate based on age
        if ($ageMonths <= 24) {
            return [
                'L' => $isMale ? -1.0 : -0.8,
                'M' => $isMale ? 16.5 : 16.2,
                'S' => $isMale ? 0.08 : 0.09,
            ];
        } elseif ($ageMonths <= 60) {
            return [
                'L' => $isMale ? -0.5 : -0.3,
                'M' => $isMale ? 15.8 : 15.5,
                'S' => $isMale ? 0.10 : 0.11,
            ];
        } else {
            return [
                'L' => $isMale ? 0.5 : 0.7,
                'M' => $isMale ? 17.0 : 16.8,
                'S' => $isMale ? 0.12 : 0.13,
            ];
        }
    }

    /**
     * Get weight reference data
     */
    private function getWeightReferenceData($ageMonths, $gender)
    {
        $isMale = strtolower($gender) === 'male';
        
        if ($ageMonths <= 12) {
            return [
                'L' => 0.3,
                'M' => $isMale ? 9.6 : 8.9,
                'S' => 0.12,
            ];
        } else {
            return [
                'L' => 0.5,
                'M' => $isMale ? 12.0 : 11.3,
                'S' => 0.13,
            ];
        }
    }

    /**
     * Get height reference data
     */
    private function getHeightReferenceData($ageMonths, $gender)
    {
        $isMale = strtolower($gender) === 'male';
        
        if ($ageMonths <= 24) {
            return [
                'L' => 1.0,
                'M' => $isMale ? 85.0 : 83.5,
                'S' => 0.04,
            ];
        } else {
            return [
                'L' => 1.0,
                'M' => $isMale ? 110.0 : 108.0,
                'S' => 0.04,
            ];
        }
    }

    /**
     * Get head circumference reference data
     */
    private function getHeadCircumferenceReferenceData($ageMonths, $gender)
    {
        $isMale = strtolower($gender) === 'male';
        
        return [
            'L' => 1.0,
            'M' => $isMale ? 46.0 : 45.5,
            'S' => 0.03,
        ];
    }
}
