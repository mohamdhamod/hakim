<!-- Sidebar -->
<div class="sidenav-menu" id="sidenavMenu">
    <div class="scrollbar" style="height: calc(100% - 41px); overflow-y: auto;">
        <!-- User Profile -->
        <div class="sidenav-user text-nowrap border border-dashed rounded-3">
            <a href="#" class="sidenav-user-name d-flex align-items-center text-decoration-none">
                @if(auth()->user()->hasRole(\App\Enums\RoleEnum::ADMIN))
                    <img src="{{auth()->user()->profile_photo_url }}"
                         width="36" alt="{{ __('translation.layout.sidebar.user_image_alt') }}"
                         class="rounded-circle me-2 d-flex">
                @endif
                <div>
                    <h5 class="my-0 fw-semibold text-body text-capitalize">
                        {{ auth()->user()->first_name }}
                    </h5>
                    <h6 class="my-0 text-muted">
                        {{ implode(', ', auth()->user()->getRoleNames()->toArray()) }}
                    </h6>
                </div>
            </a>
        </div>


        <!-- Navigation Menu -->
        <ul class="side-nav">
            @can(\App\Enums\PermissionEnum::SETTING_VIEW)
                <li class="side-nav-item">
                    <a href="{{ route('dashboard') }}" class="side-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="menu-icon bi bi-speedometer2"></i>
                        <span class="menu-text">{{ __('translation.sidebar.dashboard') }}</span>
                    </a>
                </li>
            @endcan

            @canany([
                \App\Enums\PermissionEnum::MANAGE_CLINICS_VIEW,
            ])
                <li class="side-nav-title mt-3">{{ __('translation.sidebar.browse_clinics') }}</li>

                <li class="side-nav-item">
                    <a href="{{ route('clinics.index') }}" class="side-nav-link {{ request()->routeIs('clinics.index') ? 'active' : '' }}">
                        <i class="menu-icon bi bi-building"></i>
                        <span class="menu-text">{{ __('translation.dashboard.clinics') }}</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('clinics.pending') }}" class="side-nav-link {{ request()->routeIs('clinics.pending') ? 'active' : '' }}">
                        <i class="menu-icon bi bi-hourglass-split"></i>
                        <span class="menu-text">{{ __('translation.dashboard.pending_clinics') }}</span>
                    </a>
                </li>
            @endcanany

            @canany([
                \App\Enums\PermissionEnum::MANAGE_SPECIALTIES_VIEW,
            ])
                <li class="side-nav-title mt-3">{{ __('translation.sidebar.medical_types') }}</li>

                <li class="side-nav-item">
                    <button class="side-nav-link {{ request()->routeIs('specialties.*') || request()->routeIs('chronic_disease_types.*') || request()->routeIs('lab_test_types.*') || request()->routeIs('vaccination_types.*') ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse"
                            data-bs-target="#medicalTypesMenu" aria-expanded="{{ request()->routeIs('specialties.*') || request()->routeIs('chronic_disease_types.*') || request()->routeIs('lab_test_types.*') || request()->routeIs('vaccination_types.*') ? 'true' : 'false' }}">
                        <i class="menu-icon bi bi-clipboard2-pulse"></i>
                        <span class="menu-text">{{ __('translation.sidebar.medical_types') }}</span>
                        <span class="menu-arrow">
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>
                    <div class="collapse {{ request()->routeIs('specialties.*') || request()->routeIs('chronic_disease_types.*') || request()->routeIs('lab_test_types.*') || request()->routeIs('vaccination_types.*') ? 'show' : '' }}" id="medicalTypesMenu">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ route('specialties.index') }}" class="side-nav-link {{ request()->routeIs('specialties.*') ? 'active' : '' }}">
                                    <i class="menu-icon bi bi-hospital"></i>
                                    <span class="menu-text">{{ __('translation.sidebar.manage_specialties') }}</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('chronic_disease_types.index') }}" class="side-nav-link {{ request()->routeIs('chronic_disease_types.*') ? 'active' : '' }}">
                                    <i class="menu-icon bi bi-heart-pulse"></i>
                                    <span class="menu-text">{{ __('translation.sidebar.chronic_diseases') }}</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('lab_test_types.index') }}" class="side-nav-link {{ request()->routeIs('lab_test_types.*') ? 'active' : '' }}">
                                    <i class="menu-icon bi bi-droplet"></i>
                                    <span class="menu-text">{{ __('translation.sidebar.lab_tests') }}</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('vaccination_types.index') }}" class="side-nav-link {{ request()->routeIs('vaccination_types.*') ? 'active' : '' }}">
                                    <i class="menu-icon bi bi-shield-plus"></i>
                                    <span class="menu-text">{{ __('translation.sidebar.vaccinations') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcanany

            @canany([
                \App\Enums\PermissionEnum::SETTING_VIEW,
                \App\Enums\PermissionEnum::MANAGE_ROLES,
                \App\Enums\PermissionEnum::USERS_VIEW,
            ])
                <li class="side-nav-title mt-3">{{ __('translation.sidebar.settings') }}</li>

                <li class="side-nav-item">
                    <button class="side-nav-link" type="button" data-bs-toggle="collapse"
                            data-bs-target="#configurationsMenu" aria-expanded="false">
                        <i class="menu-icon bi bi-sliders2"></i>
                        <span class="menu-text">{{ __('translation.sidebar.configurations') }}</span>
                        <span class="menu-arrow">
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>
                    <div class="collapse" id="configurationsMenu">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ route('config_titles.index') }}"
                                   class="side-nav-link {{ request()->routeIs('config_titles.index') ? 'active' : '' }}">
                                    <i class="menu-icon bi bi-tags"></i>
                                    <span class="menu-text">{{ __('translation.sidebar.titles') }}</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('config_images.index') }}"
                                   class="side-nav-link {{ request()->routeIs('config_images.index') ? 'active' : '' }}">
                                    <i class="menu-icon bi bi-image"></i>
                                    <span class="menu-text">{{ __('translation.sidebar.images') }}</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('config_email_links.index') }}"
                                   class="side-nav-link {{ request()->routeIs('config_email_links.index') ? 'active' : '' }}">
                                    <i class="menu-icon bi bi-envelope"></i>
                                    <span class="menu-text"> {{ __('translation.sidebar.emails_links_phone') }}</span>
                                </a>
                            </li>
                            @can(\App\Enums\PermissionEnum::USERS_VIEW)
                                <li class="side-nav-item">
                                    <a class="side-nav-link" href="{{ route('users.index') }}">
                                        <i class="menu-icon bi bi-people"></i>
                                        <span class="menu-text">{{ __('translation.sidebar.users') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can(\App\Enums\PermissionEnum::MANAGE_ROLES)
                                <li class="side-nav-item">
                                    <a class="side-nav-link" href="{{ route('roles.index') }}">
                                        <i class="menu-icon bi bi-shield-lock"></i>
                                        <span class="menu-text">{{ __('translation.sidebar.roles') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can(\App\Enums\PermissionEnum::SETTING_VIEW)
                                <li class="side-nav-item">
                                    <a class="side-nav-link" href="{{ route('configurations.index') }}">
                                        <i class="menu-icon bi bi-gear"></i>
                                        <span class="menu-text">{{ __('translation.sidebar.configurations') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can(\App\Enums\PermissionEnum::SETTING_VIEW)
                                <li class="side-nav-item">
                                    <a class="side-nav-link" href="{{ route('countries.index') }}">
                                        <i class="menu-icon bi bi-globe"></i>
                                        <span class="menu-text">{{ __('translation.sidebar.countries') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany
        </ul>
    </div>

    <!-- Collapse Toggle -->
    <div class="menu-collapse-box d-none d-xl-block border-top">
        <button class="button-collapse-toggle w-100 text-start"
                onclick="toggleSidebar()"
                data-text-collapse="{{ __('translation.layout.sidebar.collapse_menu') }}"
                data-text-expand="{{ __('translation.layout.sidebar.expand_menu') }}">
            <i class="bi bi-chevron-double-left"></i>
            <span>{{ __('translation.layout.sidebar.collapse_menu') }}</span>
        </button>
    </div>
</div>
