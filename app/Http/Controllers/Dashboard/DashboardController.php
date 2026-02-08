<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Specialty;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\PermissionEnum;

/**
 * Admin Dashboard Controller
 * 
 * Professional dashboard with clinic analytics
 */
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:' . PermissionEnum::SETTING_VIEW);
    }

    public function index()
    {
        // Basic Statistics
        $statistics = $this->getBasicStatistics();
        
        // Growth Statistics (compared to last month)
        $growthStats = $this->getGrowthStatistics();
        
        // Chart Data - Last 30 days appointments
        $appointmentChartData = $this->getAppointmentChartData();
        
        // Chart Data - Last 12 months clinics
        $clinicChartData = $this->getClinicChartData();
        
        // Top Specialties
        $topSpecialties = $this->getTopSpecialties();
        
        // Recent Appointments
        $recentAppointments = Appointment::with(['patient', 'clinic'])
            ->latest()
            ->take(8)
            ->get();
        
        // Recent Users
        $recentUsers = User::latest()
            ->take(5)
            ->get();
        
        // System Health
        $systemHealth = $this->getSystemHealth();
        
        // Activity Timeline
        $activityTimeline = $this->getActivityTimeline();

        return view('dashboard.index', compact(
            'statistics',
            'growthStats',
            'appointmentChartData',
            'clinicChartData',
            'topSpecialties',
            'recentAppointments',
            'recentUsers',
            'systemHealth',
            'activityTimeline'
        ));
    }

    /**
     * Get basic statistics
     */
    protected function getBasicStatistics(): array
    {
        return [
            'users' => User::count(),
            'clinics' => Clinic::approved()->count(),
            'appointments' => Appointment::count(),
            'patients' => Patient::count(),
            'specialties' => Specialty::where('active', true)->count(),
            'pending_clinics' => Clinic::pending()->count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'week_appointments' => Appointment::whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month_appointments' => Appointment::whereMonth('appointment_date', now()->month)->whereYear('appointment_date', now()->year)->count(),
        ];
    }

    /**
     * Get growth statistics compared to last period
     */
    protected function getGrowthStatistics(): array
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();
        
        // Users growth
        $usersThisMonth = User::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();
        $usersLastMonth = User::whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->count();
        $usersGrowth = $usersLastMonth > 0 ? round((($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100, 1) : 100;
        
        // Appointments growth
        $appointmentsThisMonth = Appointment::whereMonth('appointment_date', $now->month)->whereYear('appointment_date', $now->year)->count();
        $appointmentsLastMonth = Appointment::whereMonth('appointment_date', $lastMonth->month)->whereYear('appointment_date', $lastMonth->year)->count();
        $appointmentsGrowth = $appointmentsLastMonth > 0 ? round((($appointmentsThisMonth - $appointmentsLastMonth) / $appointmentsLastMonth) * 100, 1) : 100;
        
        // Clinics growth
        $clinicsThisMonth = Clinic::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();
        $clinicsLastMonth = Clinic::whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->count();
        $clinicsGrowth = $clinicsLastMonth > 0 ? round((($clinicsThisMonth - $clinicsLastMonth) / $clinicsLastMonth) * 100, 1) : 100;
        
        return [
            'users' => ['current' => $usersThisMonth, 'previous' => $usersLastMonth, 'growth' => $usersGrowth],
            'appointments' => ['current' => $appointmentsThisMonth, 'previous' => $appointmentsLastMonth, 'growth' => $appointmentsGrowth],
            'clinics' => ['current' => $clinicsThisMonth, 'previous' => $clinicsLastMonth, 'growth' => $clinicsGrowth],
        ];
    }

    /**
     * Get appointment chart data for last 30 days
     */
    protected function getAppointmentChartData(): array
    {
        $data = Appointment::select(
            DB::raw('DATE(appointment_date) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('appointment_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $values = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            $values[] = $data[$date] ?? 0;
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'total' => array_sum($values),
        ];
    }

    /**
     * Get clinic chart data for last 12 months
     */
    protected function getClinicChartData(): array
    {
        $data = Clinic::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $values = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            $found = $data->first(function ($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });
            
            $values[] = $found ? $found->count : 0;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /**
     * Get top specialties by clinic count
     */
    protected function getTopSpecialties(): \Illuminate\Support\Collection
    {
        return Specialty::withCount('clinics')
            ->where('active', true)
            ->orderByDesc('clinics_count')
            ->take(5)
            ->get();
    }

    /**
     * Get system health metrics
     */
    protected function getSystemHealth(): array
    {
        $avgResponseTime = 0.8; // Placeholder - can be calculated from logs
        $successRate = 98.5; // Placeholder
        
        return [
            'api_status' => 'operational',
            'database_status' => 'operational',
            'queue_status' => 'operational',
            'avg_response_time' => $avgResponseTime,
            'success_rate' => $successRate,
            'last_error' => null,
        ];
    }

    /**
     * Get recent activity timeline
     */
    protected function getActivityTimeline(): \Illuminate\Support\Collection
    {
        $activities = collect();
        
        // Recent appointments
        $recentAppointments = Appointment::with(['patient', 'clinic'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($appointment) {
                return [
                    'type' => 'appointment',
                    'icon' => 'bi-calendar-check',
                    'color' => 'info',
                    'message' => ($appointment->patient->name ?? 'Patient') . ' booked an appointment',
                    'time' => $appointment->created_at,
                ];
            });
        
        // Recent users
        $recentUsers = User::latest()
            ->take(3)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'icon' => 'bi-person-plus',
                    'color' => 'success',
                    'message' => $user->name . ' joined the platform',
                    'time' => $user->created_at,
                ];
            });
        
        // Recent clinics
        $recentClinics = Clinic::with('user')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($clinic) {
                return [
                    'type' => 'clinic',
                    'icon' => 'bi-hospital',
                    'color' => 'warning',
                    'message' => ($clinic->name ?? 'Clinic') . ' registered',
                    'time' => $clinic->created_at,
                ];
            });
        
        return $activities
            ->merge($recentAppointments)
            ->merge($recentUsers)
            ->merge($recentClinics)
            ->sortByDesc('time')
            ->take(8)
            ->values();
    }
}
