<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-beasties-container="" data-skin="shadcn" data-bs-theme="light" data-layout-position="fixed"
      data-topbar-color="light" data-sidenav-color="light" data-sidenav-size="default" data-sidenav-user="true"
      class="" dir="{{ Config::get('languages')[app()->getLocale()]['direction'] }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(config('services.recaptcha_v3.site_key'))
        <meta name="recaptcha-site-key" content="{{ config('services.recaptcha_v3.site_key') }}">
        <script src="https://www.google.com/recaptcha/api.js?render={{ urlencode(config('services.recaptcha_v3.site_key')) }}"></script>
    @endif
    <title>@yield('page_title', __('translation.app.name'))</title>
    <link rel="icon" type="image/x-icon" href="{{ $config_images[\App\Enums\ConfigEnum::FAVICON]->image_path ?? asset('images/img.png') }}" alt="{{ __('translation.app.name') }}">

    @yield('extra_meta')
    @include('layout.styles')
    @stack('styles')
</head>
<body>
<div class="wrapper">
    @yield('content')
</div>
@include('layout.scripts')
@stack('i18n')
@stack('scripts')
</body>
</html>


