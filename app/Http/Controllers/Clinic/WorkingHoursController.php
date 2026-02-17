<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\ClinicWorkingHour;
use Illuminate\Http\Request;

class WorkingHoursController extends Controller
{
    /**
     * Show working hours settings page.
     */
    public function index()
    {
        $clinic = auth()->user()->clinic;

        if (!$clinic) {
            return redirect()->route('clinic.workspace');
        }

        $workingHours = $clinic->workingHours()->get();
        $defaultSlotDuration = $workingHours->first()?->slot_duration ?? 30;

        return view('clinic.working-hours', compact('workingHours', 'defaultSlotDuration', 'clinic'));
    }

    /**
     * Save/update working hours.
     */
    public function store(Request $request)
    {
        $clinic = auth()->user()->clinic;

        if (!$clinic) {
            return response()->json(['success' => false, 'message' => __('translation.clinic.not_found')], 404);
        }

        $days = $request->input('days', []);

        foreach (range(0, 6) as $dayIndex) {
            $dayData = $days[$dayIndex] ?? [];
            $isActive = isset($dayData['is_active']);

            ClinicWorkingHour::updateOrCreate(
                [
                    'clinic_id'   => $clinic->id,
                    'day_of_week' => $dayIndex,
                ],
                [
                    'start_time'    => $dayData['start_time'] ?? '09:00',
                    'end_time'      => $dayData['end_time'] ?? '17:00',
                    'slot_duration' => $dayData['slot_duration'] ?? $request->input('default_slot_duration', 30),
                    'is_active'     => $isActive,
                ]
            );
        }

        if ($request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => __('translation.working_hours.saved'),
                'redirect' => route('clinic.working-hours.index'),
            ]);
        }

        return redirect()->route('clinic.working-hours.index')
            ->with('success', __('translation.working_hours.saved'));
    }

    /**
     * Get available slots for a specific date (public endpoint for booking).
     */
    public function availableSlots(Request $request, string $locale, int $clinicId)
    {
        $request->validate(['date' => 'required|date|after_or_equal:today']);

        $clinic = \App\Models\Clinic::findOrFail($clinicId);
        $slots = $clinic->getAvailableSlots($request->date);

        return response()->json(['success' => true, 'slots' => $slots]);
    }
}
