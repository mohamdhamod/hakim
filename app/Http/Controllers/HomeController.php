<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['']);
    }

    /**
     * Show the application dashboard.
     */
    public function index(Request $request)
    {
        try {
            // Stats for home page
            $stats = [
                'clinics_count' => Clinic::count(),
                'doctors_count' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->count(),
                'patients_count' => Patient::count(),
            ];
            
            // Featured specialties
            $featuredSpecialties = Specialty::take(8)
                ->get();

            return view('home.index', compact('stats', 'featuredSpecialties'));
        } catch (\Exception $e) {
            Log::error('HomeController index error: ' . $e->getMessage());
            
            // Fallback with minimal data
            return view('home.index', [
                'stats' => ['clinics_count' => 0, 'doctors_count' => 0, 'patients_count' => 0],
                'featuredSpecialties' => collect([]),
            ]);
        }
    }

    /**
     * Check user login status
     */
    public function checkLoginStatus(): JsonResponse
    {
        return response()->json([
            'authenticated' => auth()->check(),
            'login_url' => route('login'),
            'register_url' => route('register'),
        ]);
    }

}

