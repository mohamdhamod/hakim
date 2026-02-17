<!-- Clinic Sidebar -->
<div class="sidenav-menu" id="sidenavMenu">
    <div class="scrollbar" style="height: calc(100% - 41px); overflow-y: auto;">
        <!-- User Profile -->
        <div class="sidenav-user text-nowrap border border-dashed rounded-3">
            <a href="#" class="sidenav-user-name d-flex align-items-center text-decoration-none">
                <img src="{{ auth()->user()->full_path }}"
                     width="36" alt="{{ __('translation.layout.sidebar.user_image_alt') }}"
                     class="rounded-circle me-2 d-flex">
                <div>
                    <h5 class="my-0 fw-semibold text-body text-capitalize">
                        {{ auth()->user()->first_name }}
                    </h5>
                    <h6 class="my-0 text-muted">
                        {{ auth()->user()->clinic?->display_name ?? __('translation.user.type.doctor') }}
                    </h6>
                </div>
            </a>
        </div>

        <!-- Navigation Menu -->
        <ul class="side-nav">
            <!-- Dashboard -->
            <li class="side-nav-title mt-3">{{ __('translation.clinic.management') }}</li>
            
            <li class="side-nav-item">
                <a href="{{ route('clinic.workspace') }}" class="side-nav-link {{ request()->routeIs('clinic.workspace') ? 'active' : '' }}">
                    <i class="menu-icon bi bi-speedometer2"></i>
                    <span class="menu-text">{{ __('translation.clinic.dashboard') }}</span>
                </a>
            </li>

            <!-- Patients -->
            <li class="side-nav-title mt-3">{{ __('translation.patient.management') }}</li>
            
            <li class="side-nav-item">
                <button class="side-nav-link {{ request()->routeIs('clinic.patients.*') ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#patientsMenu" aria-expanded="{{ request()->routeIs('clinic.patients.*') ? 'true' : 'false' }}">
                    <i class="menu-icon bi bi-people"></i>
                    <span class="menu-text">{{ __('translation.patient.patients') }}</span>
                    <span class="menu-arrow">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </button>
                <div class="collapse {{ request()->routeIs('clinic.patients.*') ? 'show' : '' }}" id="patientsMenu">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="{{ route('clinic.patients.index') }}" class="side-nav-link {{ request()->routeIs('clinic.patients.index') ? 'active' : '' }}">
                                <i class="menu-icon bi bi-list-ul"></i>
                                <span class="menu-text">{{ __('translation.common.view_all') }}</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('clinic.patients.create') }}" class="side-nav-link {{ request()->routeIs('clinic.patients.create') ? 'active' : '' }}">
                                <i class="menu-icon bi bi-person-plus"></i>
                                <span class="menu-text">{{ __('translation.patient.add_new') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Settings -->
            <li class="side-nav-title mt-3">{{ __('translation.common.settings') }}</li>

            <li class="side-nav-item">
                <a href="{{ route('clinic.ai-assistant') }}" class="side-nav-link {{ request()->routeIs('clinic.ai-assistant') ? 'active' : '' }}">
                    <i class="menu-icon bi bi-robot"></i>
                    <span class="menu-text">{{ __('translation.ai_assistant.title') }}</span>
                    <span class="badge bg-info rounded-pill ms-auto">{{ __('translation.examination.coming_soon') }}</span>
                </a>
            </li>
            
            <li class="side-nav-item">
                <a href="{{ route('clinic.settings') }}" class="side-nav-link {{ request()->routeIs('clinic.settings') ? 'active' : '' }}">
                    <i class="menu-icon bi bi-gear"></i>
                    <span class="menu-text">{{ __('translation.clinic.settings') }}</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('clinic.working-hours.index') }}" class="side-nav-link {{ request()->routeIs('clinic.working-hours.*') ? 'active' : '' }}">
                    <i class="menu-icon bi bi-clock"></i>
                    <span class="menu-text">{{ __('translation.working_hours.title') }}</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('profile.index') }}" class="side-nav-link">
                    <i class="menu-icon bi bi-person"></i>
                    <span class="menu-text">{{ __('translation.auth.profile') }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>
