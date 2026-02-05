<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ Config::get('languages')[app()->getLocale()]['direction'] }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(config('services.recaptcha_v3.site_key'))
        <meta name="recaptcha-site-key" content="{{ config('services.recaptcha_v3.site_key') }}">
        <script src="https://www.google.com/recaptcha/api.js?render={{ urlencode(config('services.recaptcha_v3.site_key')) }}"></script>
    @endif
    <title>{{ __('translation.auth.sign_up') }}</title>
    <link rel="icon" type="image/x-icon" href="{{asset('images/favicon.png')}}">
    @include('layout.auth.styles')
</head>
<body class="hold-transition register-page registe">
@yield('content')
@include('layout.auth.scripts')
@stack('scripts')

</body>
</html>
