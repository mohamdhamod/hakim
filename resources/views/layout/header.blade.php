<!-- Topbar -->
<header class="app-topbar">
@php

    $isRtl = (Config::get('languages')[app()->getLocale()]['direction'] ?? 'ltr') === 'rtl';
    $dropdownAlignClass = $isRtl ? 'dropdown-menu-start' : 'dropdown-menu-end';
    $dropdownTextAlignClass = $isRtl ? 'text-end' : 'text-start';
    $dropdownItemFlexClass = $isRtl ? 'flex-row-reverse justify-content-end' : '';
@endphp
    <div class="container-fluid topbar-menu">
        <div class="d-flex align-items-center gap-2">
            <!-- Logo -->
            <div class="logo-topbar">
                <a class="logo-dark" href="{{route('home')}}">
                            <span class="d-flex align-items-center gap-1">
                                <span class="avatar avatar-xs rounded-circle">
                                    <span class="avatar-title">
                                         <img src="{{ $config_images[\App\Enums\ConfigEnum::LOGO]->image_path ?? asset('images/img.png') }}" alt="{{ __('translation.app.name') }}" class="avatar-img rounded-circle" width="48" height="48">

                                        </span>
                                </span>
                            </span>
                </a>
                <a class="logo-light" href="{{route('home')}}">
                            <span class="d-flex align-items-center gap-1">
                                <span class="avatar avatar-xs rounded-circle">
                                    <span class="avatar-title">
                                        <img src="{{ $config_images[\App\Enums\ConfigEnum::DARK_LOGO]->image_path ?? asset('images/img.png') }}" alt="{{ __('translation.app.name') }}" class="avatar-img rounded-circle">
                                        </span>
                                </span>
                            </span>
                </a>
            </div>

            <!-- Mobile Toggle -->
            <button class="button-collapse-toggle d-xl-none" onclick="toggleMobileSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="topbar-item d-none d-lg-flex">
                <a class="topbar-link btn shadow-none btn-link px-2" href="{{ route('home') }}">{{ __('translation.layout.home.nav_home') }}</a>
            </div>
            <div class="topbar-item d-none d-lg-flex">
                <a class="topbar-link btn shadow-none btn-link px-2" href="{{ route('home.clinics') }}">{{ __('translation.sidebar.browse_clinics') ?? 'Browse Clinics' }}</a>
            </div>

            @canany([
                \App\Enums\PermissionEnum::SETTING_VIEW,
                \App\Enums\PermissionEnum::MANAGE_ROLES,
                \App\Enums\PermissionEnum::USERS_VIEW,
            ])
            <div class="topbar-item d-none d-md-flex">
                <div class="dropdown">
                    <a class="dropdown-toggle topbar-link btn shadow-none btn-link px-2"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        {{ __('translation.sidebar.settings') }}
                        <i class="bi bi-chevron-down ms-1"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('config_titles.index') }}">
                                <i class="bi bi-tags me-2"></i>
                                {{ __('translation.sidebar.titles') }}
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('config_images.index') }}">
                                <i class="bi bi-image me-2"></i>
                                {{ __('translation.sidebar.images') }}
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('config_email_links.index') }}">
                                <i class="bi bi-envelope me-2"></i>
                                {{ __('translation.sidebar.emails_links_phone') }}
                            </a>
                        </li>

                        @can(\App\Enums\PermissionEnum::USERS_VIEW)
                            <li>
                                <a class="dropdown-item" href="{{ route('users.index') }}">
                                    <i class="bi bi-people me-2"></i>
                                    {{ __('translation.sidebar.users') }}
                                </a>
                            </li>
                        @endcan

                        @can(\App\Enums\PermissionEnum::MANAGE_ROLES)
                            <li>
                                <a class="dropdown-item" href="{{ route('roles.index') }}">
                                    <i class="bi bi-shield-lock me-2"></i>
                                    {{ __('translation.sidebar.roles') }}
                                </a>
                            </li>
                        @endcan

                        @can(\App\Enums\PermissionEnum::SETTING_VIEW)
                            <li>
                                <a class="dropdown-item" href="{{ route('configurations.index') }}">
                                    <i class="bi bi-gear me-2"></i>
                                    {{ __('translation.sidebar.configurations') }}
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('countries.index') }}">
                                    <i class="bi bi-globe me-2"></i>
                                    {{ __('translation.sidebar.countries') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </div>
            @endcanany

        </div>

        <div class="d-flex align-items-center gap-2">



            <!-- Language Selector -->
<div class="topbar-item">
    <div class="dropdown">
        <button type="button" class="btn btn-light border btn-sm dropdown-toggle d-inline-flex align-items-center gap-2 rounded-pill px-3 shadow-sm" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('translation.layout.header.language_switcher_aria') }}">
            <img alt="{{ __('translation.layout.header.flag_alt') }}" height="18" width="18" id="selected-language-image" class="rounded" src="{{ asset(Config::get('languages')[App::getLocale()]['flag-img']) }}">
            <span class="fw-semibold d-none d-sm-inline" id="selected-language-name">{{ Config::get('languages')[App::getLocale()]['display'] ?? Config::get('languages')[App::getLocale()]['flag-icon'] }}</span>
            <span class="fw-semibold d-inline d-sm-none text-uppercase" id="selected-language-code">{{ Config::get('languages')[App::getLocale()]['flag-icon'] }}</span>
        </button>
        <div class="dropdown-menu {{ $dropdownAlignClass }} {{ $dropdownTextAlignClass }}">
            @foreach (Config::get('languages') as $lang => $language)
                @php
                    $availableLangs = array_keys(Config::get('languages'));
                    $segments = explode('/', request()->path());

                    if (isset($segments[0]) && in_array($segments[0], $availableLangs)) {
                        $segments[0] = $lang;
                    } else {
                        array_unshift($segments, $lang);
                    }

                    $switchUrl = url(implode('/', $segments));
                    if (request()->getQueryString()) {
                        $switchUrl .= '?' . request()->getQueryString();
                    }
                @endphp
                <a class="dropdown-item d-flex align-items-center gap-2 {{ $dropdownItemFlexClass }}" href="{{ $switchUrl }}"
                   onclick="changeLanguage('{{ $lang }}', '{{ asset($language['flag-img']) }}'); window.location.assign('{{ $switchUrl }}'); return false;">
                    <img alt="{{ __('translation.layout.header.language_flag_alt') }}" height="18" width="18" class="rounded" src="{{ asset($language['flag-img']) }}">
                    <span class="align-middle">{{ $language['display'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>


            <div class="topbar-item">
                <button type="button" class="topbar-link">
                    <i class="bi bi-moon fs-4"></i>
                </button>
            </div>



            <!-- User Profile -->
            <div class="topbar-item nav-user">
                <div class="dropdown">
                    <button class="dropdown-toggle topbar-link drop-arrow-none px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{auth()->user()->profile_photo_url }}" width="32" alt="{{ __('translation.layout.header.user_image_alt') }}" class="rounded-circle d-flex">
                    </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">{{ __('translation.layout.header.welcome_back') }}</h6></li>
                        @auth
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="bi bi-person me-2"></i> {{ __('translation.auth.profile') }}
                                </a>
                            </li>
                        @endauth

                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item fw-semibold" href="{{ route('logout') }}" onclick="event.preventDefault();const f=document.getElementById('logout-form'); if(!f) return; if (f.requestSubmit) { f.requestSubmit(); } else { const ev=new Event('submit',{bubbles:true,cancelable:true}); if (f.dispatchEvent(ev)) f.submit(); }"><i class="bi bi-box-arrow-right me-2"></i>{{ __('translation.auth.logout') }}</a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none" data-recaptcha-action="logout">@csrf</form>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
