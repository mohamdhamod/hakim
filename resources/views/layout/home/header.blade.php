@php

        $isRtl = (Config::get('languages')[app()->getLocale()]['direction'] ?? 'ltr') === 'rtl';

        $navSpacerClass = 'me-auto';
        $dropdownAlignClass = $isRtl ? 'dropdown-menu-start' : 'dropdown-menu-end';
        $dropdownTextAlignClass = $isRtl ? 'text-end' : 'text-start';
        $dropdownItemFlexClass = $isRtl ? 'flex-row-reverse justify-content-end' : '';
        $searchGroupClass = $isRtl ? 'input-group input-group-sm flex-row-reverse' : 'input-group input-group-sm';
@endphp

	<header class="topbar home-header shadow-sm bg-body border-bottom position-sticky top-0 z-3 backdrop-blur">
	<nav class="navbar navbar-expand-lg container py-2 px-3 px-lg-0">
		<a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">

            <img src="{{ $config_images[\App\Enums\ConfigEnum::LOGO]->image_path ?? asset('images/img.png') }}" alt="{{ __('translation.app.name') }}" class="avatar-img home-header-logo rounded-circle" height="48">

        </a>

		{{-- Mobile quick actions (visible on small screens) --}}
		<div class="d-flex align-items-center gap-1 ms-auto d-lg-none flex-nowrap {{ $isRtl ? 'flex-row-reverse' : '' }}">
			<button type="button"
						class="btn btn-light border btn-sm rounded-pill px-2 py-1 d-inline-flex align-items-center home-header-action-btn"
						data-bs-toggle="collapse"
						data-bs-target="#homeNavbar"
						aria-controls="homeNavbar"
						aria-expanded="false"
						aria-label="{{ __('translation.layout.home.toggle_navigation') }}">
				<i class="bi bi-list fs-6"></i>
			</button>



			<div class="dropdown home-header-action-dropdown">
				<button type="button"
						class="btn btn-light border btn-sm rounded-pill px-2 py-1 d-inline-flex align-items-center home-header-action-btn"
						data-bs-toggle="dropdown"
						aria-expanded="false"
						aria-label="{{ __('translation.layout.header.language_switcher_aria') }}">
					<img alt="{{ __('translation.layout.header.flag_alt') }}" height="18" width="18" class="rounded" src="{{ asset(Config::get('languages')[App::getLocale()]['flag-img']) }}">
				</button>
				<div class="dropdown-menu position-absolute home-header-action-menu {{ $dropdownAlignClass }} {{ $dropdownTextAlignClass }} p-1 shadow-sm border-0 rounded-3 mt-2" style="max-height: 60vh; overflow:auto;">
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
						<a class="dropdown-item d-flex align-items-center gap-2 rounded-2 py-1 px-2 {{ $dropdownItemFlexClass }}" href="{{ $switchUrl }}"
						   onclick="changeLanguage('{{ $lang }}', '{{ asset($language['flag-img']) }}'); window.location.assign('{{ $switchUrl }}'); return false;">
							<img alt="{{ __('translation.layout.header.language_flag_alt') }}" height="18" width="18" class="rounded" src="{{ asset($language['flag-img']) }}">
							<span class="align-middle">{{ $language['display'] }}</span>
						</a>
					@endforeach
				</div>
			</div>
		</div>
		<div class="collapse navbar-collapse {{ $isRtl ? 'text-end' : '' }} mt-3 mt-lg-0 pt-2 pt-lg-0" id="homeNavbar">
			<div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-3 w-100">
				<ul class="navbar-nav {{ $navSpacerClass }} mb-0">
					<li class="nav-item"><a class="nav-link" href="{{ route('home') }}">{{ __('translation.layout.home.nav_home') }}</a></li>
					<li class="nav-item"><a class="nav-link" href="{{ route('home.clinics') }}">{{ __('translation.layout.home.nav_clinics') }}</a></li>
					<li class="nav-item"><a class="nav-link" href="{{ route('about-us.index') }}">{{ __('translation.layout.home.nav_about') }}</a></li>
				</ul>

				<div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">
					{{-- Desktop actions (hidden on mobile, since mobile has quick actions) --}}
					<div class="d-none d-lg-flex align-items-center gap-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">


						@auth
							<div class="dropdown">
								<button type="button"
										class="btn btn-outline-primary btn-sm dropdown-toggle d-inline-flex align-items-center gap-2"
										data-bs-toggle="dropdown"
										aria-expanded="false">
									{{ __('translation.layout.home.profile') }}
								</button>
								<ul class="dropdown-menu home-header-action-menu {{ $dropdownAlignClass }} {{ $dropdownTextAlignClass }} p-1 shadow-sm border-0 rounded-3 small">
									<li>
										<a class="dropdown-item d-flex align-items-center gap-1 rounded-2 py-1 px-2 {{ $dropdownItemFlexClass }}" href="{{ route('profile.index') }}">
											<i class="bi bi-person fs-6"></i>
											<span>{{ __('translation.auth.profile') }}</span>
										</a>
									</li>
									<li><hr class="dropdown-divider my-1"></li>
									<li>
										<a class="dropdown-item d-flex align-items-center gap-1 rounded-2 py-1 px-2 fw-semibold {{ $dropdownItemFlexClass }}" href="{{ route('logout') }}"
										   onclick="event.preventDefault();const f=document.getElementById('home-logout-form-desktop'); if(!f) return; if (f.requestSubmit) { f.requestSubmit(); } else { const ev=new Event('submit',{bubbles:true,cancelable:true}); if (f.dispatchEvent(ev)) f.submit(); }">
											<i class="bi bi-box-arrow-right fs-6"></i>
											<span>{{ __('translation.auth.logout') }}</span>
										</a>
										<form id="home-logout-form-desktop" action="{{ route('logout') }}" method="POST" class="d-none" data-recaptcha-action="logout">@csrf</form>
									</li>
								</ul>
							</div>
						@else
							<div class="dropdown">
								<button type="button"
										class="btn btn-primary btn-sm dropdown-toggle d-inline-flex align-items-center gap-2"
										data-bs-toggle="dropdown"
										aria-expanded="false">
									{{ __('translation.layout.home.sign_in') }}
								</button>
								<ul class="dropdown-menu home-header-action-menu {{ $dropdownAlignClass }} {{ $dropdownTextAlignClass }} p-1 shadow-sm border-0 rounded-3 small">
									<li>
										<a class="dropdown-item d-flex align-items-center gap-1 rounded-2 py-1 px-2 {{ $dropdownItemFlexClass }}" href="{{ route('login') }}">
											<i class="bi bi-box-arrow-in-right fs-6"></i>
											<span>{{ __('translation.layout.home.sign_in') }}</span>
										</a>
									</li>
									<li>
										<a class="dropdown-item d-flex align-items-center gap-1 rounded-2 py-1 px-2 {{ $dropdownItemFlexClass }}" href="{{ route('register') }}">
											<i class="bi bi-person-plus fs-6"></i>
											<span>{{ __('translation.auth.create_account') }}</span>
										</a>
									</li>
								</ul>
							</div>
						@endauth

						<div class="dropdown">
							<button type="button" class="btn btn-light border btn-sm dropdown-toggle d-inline-flex align-items-center gap-2 px-3 shadow-sm" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('translation.layout.header.language_switcher_aria') }}">
								<img alt="{{ __('translation.layout.header.flag_alt') }}" height="18" width="18" class="rounded" src="{{ asset(Config::get('languages')[App::getLocale()]['flag-img']) }}">
								<span class="fw-semibold d-none d-sm-inline">{{ Config::get('languages')[App::getLocale()]['display'] ?? Config::get('languages')[App::getLocale()]['flag-icon'] }}</span>
								<span class="fw-semibold d-inline d-sm-none text-uppercase">{{ Config::get('languages')[App::getLocale()]['flag-icon'] }}</span>
							</button>
							<div class="dropdown-menu home-header-action-menu {{ $dropdownAlignClass }} {{ $dropdownTextAlignClass }} p-1 shadow-sm border-0 rounded-3">
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
									<a class="dropdown-item d-flex align-items-center gap-2 rounded-2 py-1 px-2 {{ $dropdownItemFlexClass }}" href="{{ $switchUrl }}"
									   onclick="changeLanguage('{{ $lang }}', '{{ asset($language['flag-img']) }}'); window.location.assign('{{ $switchUrl }}'); return false;">
										<img alt="{{ __('translation.layout.header.language_flag_alt') }}" height="18" width="18" class="rounded" src="{{ asset($language['flag-img']) }}">
										<span class="align-middle">{{ $language['display'] }}</span>
									</a>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</nav>
</header>
