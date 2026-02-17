<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicWorkingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id', 'day_of_week', 'start_time', 'end_time',
        'slot_duration', 'is_active',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'slot_duration' => 'integer',
        'is_active' => 'boolean',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Generate available time slots for this day.
     */
    public function getTimeSlotsAttribute(): array
    {
        if (!$this->is_active) {
            return [];
        }

        $slots = [];
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);

        while ($start->lt($end)) {
            $slots[] = $start->format('H:i');
            $start->addMinutes($this->slot_duration);
        }

        return $slots;
    }

    /**
     * Get available slots for a specific date (excluding booked ones).
     */
    public function getAvailableSlots(string $date): array
    {
        $allSlots = $this->time_slots;

        $bookedSlots = Appointment::where('clinic_id', $this->clinic_id)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->pluck('appointment_time')
            ->map(fn($t) => \Carbon\Carbon::parse($t)->format('H:i'))
            ->toArray();

        return array_values(array_diff($allSlots, $bookedSlots));
    }

    /**
     * Day name helper.
     */
    public function getDayNameAttribute(): string
    {
        $days = [
            0 => __('translation.days.sunday'),
            1 => __('translation.days.monday'),
            2 => __('translation.days.tuesday'),
            3 => __('translation.days.wednesday'),
            4 => __('translation.days.thursday'),
            5 => __('translation.days.friday'),
            6 => __('translation.days.saturday'),
        ];

        return $days[$this->day_of_week] ?? '';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
