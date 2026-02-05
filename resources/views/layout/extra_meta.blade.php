@section('extra_meta')
    <meta name="description" content="{{env('DESCRIPTION')}}">

    <!-- Google / Search Engine Tags -->
    <meta itemprop="name" content="{{ __('translation.app.name') }}">
    <meta itemprop="description" content="{{env('DESCRIPTION')}}">
    <meta itemprop="image" content="{{env('APP_URL')}}">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ __('translation.app.name') }}">
    <meta property="og:description" content="{{env('DESCRIPTION')}}">
    <meta property="og:image" content="{{ $config_images[\App\Enums\ConfigEnum::FAVICON]->image_path ?? asset('images/img.png') }}">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ __('translation.app.name') }}">
    <meta name="twitter:description" content="{{env('DESCRIPTION')}}">
    <meta name="twitter:image" content="{{ $config_images[\App\Enums\ConfigEnum::FAVICON]->image_path ?? asset('images/img.png') }}">
@endsection
