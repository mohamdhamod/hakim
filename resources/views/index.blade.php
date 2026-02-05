@extends('layout.main')
@include('layout.extra_meta')
@section('content')
    <div class="container-fluid">
        <!-- Page Title Section -->
        <div class="page-title-section">
            <div class="row justify-content-center py-5">
                <div class="col-xxl-5 col-xl-7 text-center">
                <span class="badge badge-default fw-normal shadow px-2 py-1 mb-2 fst-italic fs-xxs">
                    <i class="bi bi-stars me-1"></i> Clinic Administration
                </span>
                    <h3 class="fw-bold">Hakim Clinics Admin Dashboard</h3>
                    <p class="fs-md text-muted mb-0">Monitor appointments, clinics, and patient activity from one unified dashboard.</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards Row -->
        <div class="row">
            <!-- Charts Grid -->
            <div class="col-12">
                <div class="row">
                    <!-- Today's Appointments Chart -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Today's Appointments</h5>
                                    <i class="bi bi-calendar3 text-muted"></i>
                                </div>
                                <div>
                                    <canvas id="promptsChart" height="200"></canvas>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <div>
                                        <span class="text-muted">Total Today</span>
                                        <h4 class="mb-0">1,245</h4>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-muted">vs Yesterday</span>
                                        <div class="d-flex align-items-center justify-content-end">
                                            <i class="bi bi-arrow-up text-success me-1"></i>
                                            <span class="text-success">+12%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Rate Chart -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Attendance Rate</h5>
                                    <i class="bi bi-graph-up text-muted"></i>
                                </div>
                                <div>
                                    <canvas id="accuracyChart" height="200"></canvas>
                                </div>

                                <div class="text-center mt-3">
                                    <h3 class="mb-0">94.3%</h3>
                                    <span class="text-muted">Average Attendance</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- New Patients Chart -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">New Patients</h5>
                                    <i class="bi bi-person-plus text-muted"></i>
                                </div>
                                <div>
                                    <canvas id="tokenChart" height="200"></canvas>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <div>
                                        <span class="text-muted">New Today</span>
                                        <h4 class="mb-0">214</h4>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-muted">vs Yesterday</span>
                                        <div class="d-flex align-items-center justify-content-end">
                                            <i class="bi bi-arrow-up text-success me-1"></i>
                                            <span class="text-success">+6.4%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Requests Chart -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Booking Requests</h5>
                                    <i class="bi bi-calendar-check text-muted"></i>
                                </div>
                                <div>
                                    <canvas id="requestsChart" height="200"></canvas>
                                </div>

                                <div class="text-center mt-3">
                                    <h3 class="mb-0">3,148</h3>
                                    <span class="text-muted">Total Requests Today</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Statistics Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-xl-3 col-md-6">
                                <div class="text-center">
                                    <p class="mb-4 d-flex align-items-center gap-1 justify-content-center">
                                        <i class="bi bi-calendar-check"></i> Booking Requests
                                    </p>
                                    <h2 class="fw-bold mb-0">18,762</h2>
                                    <p class="text-muted">Total booking requests in last 30 days</p>
                                    <p class="mb-0 mt-4 d-flex align-items-center gap-1 justify-content-center">
                                        <i class="bi bi-calendar"></i> Data from May
                                    </p>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 order-xl-last">
                                <div class="text-center">
                                    <p class="mb-4 d-flex align-items-center gap-1 justify-content-center">
                                        <i class="bi bi-building"></i> Active Clinics
                                    </p>
                                    <h2 class="fw-bold mb-0">128 Clinics</h2>
                                    <p class="text-muted">Active in the last 30 days</p>
                                    <p class="mb-0 mt-4 d-flex align-items-center gap-1 justify-content-center">
                                        <i class="bi bi-clock-history"></i> Last update: 12.06.2025
                                    </p>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="w-100" style="height: 240px;">
                                    <!-- Chart placeholder -->
                                    <div class="placeholder-chart" style="height: 240px;">
                                        <canvas id="requestTrendsChart" style="width: 562px; display: block; box-sizing: border-box; height: 240px;" height="300" width="703"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex align-items-center text-muted justify-content-between">
                            <div>Last update: 16.06.2025</div>
                            <div>You received 2 new patient feedback reports</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Sessions & Model Usage Section -->
        <div class="row">
            <div class="col-xxl-6">
                <!-- Recent Appointments -->
                <div class="card">
                    <div class="card-header justify-content-between align-items-center border-dashed">
                        <h4 class="card-title mb-0">Recent Appointments</h4>
                        <div class="d-flex gap-2">
                            <a href="javascript:void(0);" class="btn btn-sm btn-light">
                                <i class="bi bi-plus me-1"></i> New Appointment
                            </a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-primary">
                                <i class="bi bi-download me-1"></i> Export Report
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-centered table-custom table-sm table-nowrap table-hover mb-0">
                                <tbody>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-1.jpg" alt="Alice Cooper"><div><span class="text-muted fs-xs">Alice Cooper</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#APT-5001</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Clinic</span><h5 class="fs-base mb-0 fw-normal">City Dental Clinic</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-01</h5></td>
                                    <td><span class="text-muted fs-xs">Duration</span><h5 class="fs-base mb-0 fw-normal">30 min</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-success fs-xs me-1"></i> Completed</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-2.jpg" alt="Bob Smith"><div><span class="text-muted fs-xs">Bob Smith</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#APT-5002</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Clinic</span><h5 class="fs-base mb-0 fw-normal">Wellness Family Clinic</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-02</h5></td>
                                    <td><span class="text-muted fs-xs">Duration</span><h5 class="fs-base mb-0 fw-normal">45 min</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-warning fs-xs me-1"></i> Pending</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-3.jpg" alt="Carol Lee"><div><span class="text-muted fs-xs">Carol Lee</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#APT-5003</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Clinic</span><h5 class="fs-base mb-0 fw-normal">SkinCare Center</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-03</h5></td>
                                    <td><span class="text-muted fs-xs">Duration</span><h5 class="fs-base mb-0 fw-normal">20 min</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-danger fs-xs me-1"></i> Cancelled</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-4.jpg" alt="David Kim"><div><span class="text-muted fs-xs">David Kim</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#APT-5004</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Clinic</span><h5 class="fs-base mb-0 fw-normal">Ortho Plus</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-04</h5></td>
                                    <td><span class="text-muted fs-xs">Duration</span><h5 class="fs-base mb-0 fw-normal">60 min</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-success fs-xs me-1"></i> Completed</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-5.jpg" alt="Eva Green"><div><span class="text-muted fs-xs">Eva Green</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#APT-5005</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Clinic</span><h5 class="fs-base mb-0 fw-normal">Pediatric Care</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-05</h5></td>
                                    <td><span class="text-muted fs-xs">Duration</span><h5 class="fs-base mb-0 fw-normal">25 min</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-success fs-xs me-1"></i> Completed</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                <tr>
                                    <td><div class="d-flex align-items-center"><img class="avatar-sm rounded-circle me-2" src="https://coderthemes.com/vona-angular/assets/images/users/user-6.jpg" alt="Fiona White"><div><span class="text-muted fs-xs">Fiona White</span><h5 class="fs-base mb-0"><a class="text-body" href="/dashboard">#APT-5006</a></h5></div></div></td>
                                    <td><span class="text-muted fs-xs">Clinic</span><h5 class="fs-base mb-0 fw-normal">Heart Health Clinic</h5></td>
                                    <td><span class="text-muted fs-xs">Date</span><h5 class="fs-base mb-0 fw-normal">2025-05-06</h5></td>
                                    <td><span class="text-muted fs-xs">Duration</span><h5 class="fs-base mb-0 fw-normal">40 min</h5></td>
                                    <td><span class="text-muted fs-xs">Status</span><h5 class="fs-base mb-0 fw-normal d-flex align-items-center"><i class="bi bi-circle-fill text-success fs-xs me-1"></i> Completed</h5></td>
                                    <td style="width: 30px;"><div class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical fs-lg"></i></a><div class="dropdown-menu dropdown-menu-end"><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.view_details') }}</a><a href="javascript:void(0)" class="dropdown-item">{{ __('translation.general.delete') }}</a></div></div></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-0">
                        <div class="align-items-center justify-content-between row text-center text-sm-start">
                            <div class="col-sm">
                                <div class="text-muted">
                                    Showing <span class="fw-semibold">1</span> to <span class="fw-semibold">6</span> of <span class="fw-semibold">128</span> Appointments
                                </div>
                            </div>
                            <div class="col-sm-auto mt-3 mt-sm-0">
                                <nav>
                                    <ul class="pagination pagination-sm pagination-boxed mb-0 justify-content-center">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-6">
                <!-- Clinic Performance Summary -->
                <div class="card mb-4">
                    <div class="card-header border-dashed">
                        <h4 class="card-title mb-0">Clinic Performance Summary</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-centered table-custom table-nowrap mb-0">
                                <thead class="bg-light-subtle thead-sm">
                                <tr class="text-uppercase fs-xxs">
                                    <th>Clinic</th>
                                    <th>Appointments</th>
                                    <th>Revenue ($)</th>
                                    <th>Avg. Visit (min)</th>
                                    <th>Last Activity</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>City Dental Clinic</td>
                                    <td>128</td>
                                    <td>12,480</td>
                                    <td>35</td>
                                    <td>2025-06-15</td>
                                </tr>
                                <tr>
                                    <td>Wellness Family Clinic</td>
                                    <td>214</td>
                                    <td>21,350</td>
                                    <td>28</td>
                                    <td>2025-06-14</td>
                                </tr>
                                <tr>
                                    <td>SkinCare Center</td>
                                    <td>96</td>
                                    <td>9,120</td>
                                    <td>20</td>
                                    <td>2025-06-13</td>
                                </tr>
                                <tr>
                                    <td>Ortho Plus</td>
                                    <td>63</td>
                                    <td>14,700</td>
                                    <td>55</td>
                                    <td>2025-06-12</td>
                                </tr>
                                <tr>
                                    <td>Physio Plus</td>
                                    <td>74</td>
                                    <td>8,220</td>
                                    <td>45</td>
                                    <td>2025-06-10</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top-0 text-end">
                        <span class="text-muted">Updated 1 hour ago</span>
                    </div>
                </div>

                <!-- Platform Performance Metrics -->
                <div class="card">
                    <div class="card-header border-dashed">
                        <h4 class="card-title mb-0">Platform Performance Metrics</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-centered table-nowrap table-custom mb-0">
                                <thead class="bg-light-subtle thead-sm">
                                <tr class="text-uppercase fs-xxs">
                                    <th>Service</th>
                                    <th>Avg. Response</th>
                                    <th>Requests</th>
                                    <th>Error Rate</th>
                                    <th>Uptime (%)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Booking API</td>
                                    <td>320ms</td>
                                    <td>8,204</td>
                                    <td>0.18%</td>
                                    <td>99.9</td>
                                </tr>
                                <tr>
                                    <td>Notifications</td>
                                    <td>410ms</td>
                                    <td>1,029</td>
                                    <td>0.03%</td>
                                    <td>99.8</td>
                                </tr>
                                <tr>
                                    <td>Payments</td>
                                    <td>680ms</td>
                                    <td>489</td>
                                    <td>0.00%</td>
                                    <td>99.7</td>
                                </tr>
                                <tr>
                                    <td>Clinic Search</td>
                                    <td>280ms</td>
                                    <td>2,170</td>
                                    <td>0.10%</td>
                                    <td>99.9</td>
                                </tr>
                                <tr>
                                    <td>Reports</td>
                                    <td>350ms</td>
                                    <td>5,025</td>
                                    <td>0.01%</td>
                                    <td>99.8</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top-0 text-end">
                        <span class="text-muted">API stats updated: 2025-06-16 08:32 AM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('scripts')

@endpush
