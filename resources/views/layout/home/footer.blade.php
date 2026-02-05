<footer class="site-footer border-top bg-body mt-5 footer-gradient">
	<div class="container py-3">
		<!-- Mobile Layout -->
		<div class="d-md-none">
			<!-- Logo & Description -->
			<div class="text-center mb-3">
				<a class="navbar-brand d-inline-flex align-items-center gap-2 mb-2" href="{{ url('/') }}">
					<img src="{{ $config_images[\App\Enums\ConfigEnum::LOGO]->image_path ?? asset('images/img.png') }}" alt="{{ __('translation.app.name') }}" class="avatar-img rounded-circle" height="40">
				</a>
				<p class="text-muted small mb-0 px-3">{{ __('translation.layout.home.footer.about_description') }}</p>
			</div>
			
			<!-- Contact Info -->
			@php
				$infoEmail = $links[\App\Enums\ConfigEnum::INFO_EMAIL]->name ?? null;
			@endphp
			<div class="d-flex justify-content-center flex-wrap gap-3 mb-3 small">
				
				@if($infoEmail)
				<a class="text-decoration-none text-primary d-flex align-items-center gap-1" href="mailto:{{ $infoEmail }}">
					<i class="bi bi-envelope"></i>{{ $infoEmail }}
				</a>
				@endif
			</div>
			
			<!-- Social Links -->
			@php
				$socialLinks = [
					\App\Enums\ConfigEnum::FOOTER_FACEBOOK => ['icon' => 'bi bi-facebook', 'color' => 'primary'],
					\App\Enums\ConfigEnum::FOOTER_INSTAGRAM => ['icon' => 'bi bi-instagram', 'color' => 'danger'],
					\App\Enums\ConfigEnum::FOOTER_TWITTER => ['icon' => 'bi bi-twitter-x', 'color' => 'dark'],
					\App\Enums\ConfigEnum::FOOTER_LINKED_IN => ['icon' => 'bi bi-linkedin', 'color' => 'primary'],
				];
			@endphp
			<div class="d-flex justify-content-center gap-3 mb-3">
				@foreach($socialLinks as $key => $social)
					@php $href = $links[$key]->name ?? null; @endphp
					@if($href)
					<a href="{{ $href }}" target="_blank" rel="noopener" class="text-{{ $social['color'] }} fs-5">
						<i class="{{ $social['icon'] }}"></i>
					</a>
					@endif
				@endforeach
			</div>
			
			<!-- Quick Links -->
			<div class="d-flex justify-content-center flex-wrap gap-3 mb-2 px-2">
				<a class="text-decoration-none text-muted" href="{{ route('home') }}">{{ __('translation.layout.home.nav_home') }}</a>
				<a class="text-decoration-none text-muted" href="{{ route('about-us.index') }}">{{ __('translation.layout.home.footer.about') }}</a>
				<a class="text-decoration-none text-muted" href="{{ route('privacy-policy.index') }}">{{ __('translation.layout.home.footer.privacy_policy') }}</a>
			</div>
		</div>
		
		<!-- Desktop Layout -->
		<div class="d-none d-md-block">
			<div class="row g-3 align-items-start">
				<div class="col-md-3">
					<a class="navbar-brand d-flex align-items-center gap-2 mb-2" href="{{ url('/') }}">
						<img src="{{ $config_images[\App\Enums\ConfigEnum::LOGO]->image_path ?? asset('images/img.png') }}" alt="{{ __('translation.app.name') }}" class="avatar-img rounded-circle" height="36">
					</a>
					<p class="text-muted small mb-0">{{ __('translation.layout.home.footer.about_description') }}</p>
				</div>
				<div class="col-md-2">
					<h6 class="fw-bold small mb-2">{{ __('translation.layout.home.footer.explore') }}</h6>
					<ul class="list-unstyled mb-0 small">
						<li><a class="text-decoration-none text-muted hover-primary" href="{{ route('home') }}">{{ __('translation.layout.home.nav_home') }}</a></li>
						<li><a class="text-decoration-none text-muted hover-primary" href="{{ route('about-us.index') }}">{{ __('translation.layout.home.footer.about') }}</a></li>
					</ul>
				</div>
				<div class="col-md-3">
					<h6 class="fw-bold small mb-2">{{ __('translation.layout.home.footer.legal') }}</h6>
					<ul class="list-unstyled mb-0 small">
						<li><a class="text-decoration-none text-muted hover-primary" href="{{ route('terms-conditions.index') }}">{{ __('translation.layout.home.footer.terms_conditions') }}</a></li>
						<li><a class="text-decoration-none text-muted hover-primary" href="{{ route('privacy-policy.index') }}">{{ __('translation.layout.home.footer.privacy_policy') }}</a></li>
					</ul>
				</div>
				<div class="col-md-4">
					@php
						$infoEmail = $links[\App\Enums\ConfigEnum::INFO_EMAIL]->name ?? null;
					@endphp
					<h6 class="fw-bold small mb-2">{{ __('translation.layout.home.footer.contact') }}</h6>
					<ul class="list-unstyled mb-2 small">
					
						@if($infoEmail)
						<li>
							<a class="text-decoration-none text-muted d-inline-flex align-items-center gap-2" href="mailto:{{ $infoEmail }}">
								<i class="bi bi-envelope text-primary"></i>{{ $infoEmail }}
							</a>
						</li>
						@endif
					</ul>
					@php
						$socialLinks = [
							\App\Enums\ConfigEnum::FOOTER_TWITTER => ['icon' => 'bi bi-twitter-x', 'color' => 'secondary'],
							\App\Enums\ConfigEnum::FOOTER_LINKED_IN => ['icon' => 'bi bi-linkedin', 'color' => 'primary'],
							\App\Enums\ConfigEnum::FOOTER_FACEBOOK => ['icon' => 'bi bi-facebook', 'color' => 'primary'],
							\App\Enums\ConfigEnum::FOOTER_INSTAGRAM => ['icon' => 'bi bi-instagram', 'color' => 'danger'],
						];
					@endphp
					<div class="d-flex gap-3">
						@foreach($socialLinks as $key => $social)
							@php $href = $links[$key]->name ?? null; @endphp
							@if($href)
							<a href="{{ $href }}" target="_blank" rel="noopener" class="text-{{ $social['color'] }} fs-5">
								<i class="{{ $social['icon'] }}"></i>
							</a>
							@endif
						@endforeach
					</div>
				</div>
			</div>
		</div>
		
		<hr class="my-2 opacity-25">
		<div class="d-flex justify-content-center text-muted small">
			<span>{{ __('translation.layout.home.footer.rights', ['year' => date('Y')]) }}</span>
		</div>
	</div>
</footer>

