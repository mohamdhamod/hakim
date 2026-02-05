<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicHomeController extends Controller
{
    /**
     * Display the clinic homepage with clinic listings.
     */
    public function index(Request $request)
    {
        $query = Clinic::with(['doctor', 'specialty'])
            ->where('status', 'approved')
            ->withCount('patients');

        // Filter by specialty
        if ($request->filled('specialty')) {
            $query->where('specialty_id', $request->specialty);
        }

        // Search by clinic name or doctor name
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('doctor', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $clinics = $query->orderBy('created_at', 'desc')->paginate(12);
        $specialties = Specialty::active()->ordered()->get();

        return view('home.clinics', compact('clinics', 'specialties'));
    }

    /**
     * Show clinic details.
     */
    public function show(Clinic $clinic)
    {
        if ($clinic->status !== 'approved') {
            abort(404);
        }

        $clinic->load(['doctor', 'specialty']);

        return view('home.clinic-details', compact('clinic'));
    }
}
