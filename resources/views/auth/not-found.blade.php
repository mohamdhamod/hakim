@extends('layout.auth.main')
@include('layout.extra_meta')
@section('content')

    <div class="auth-box overflow-hidden align-items-center d-flex min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-md-6 col-sm-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center p-4">

                            <!-- Error Section -->
                            <div class="p-2">
                                <div class="fw-bold display-3 text-primary mb-2">
                                    <i class="bi bi-exclamation-triangle-fill text-danger me-1"></i>
                                    {{ $error_code ?? 404 }}
                                </div>
                                <h3 class="fw-semibold mb-2">{{ $title ?? __('translation.messages.page_not_found_title') }}</h3>
                                <p class="text-muted mb-4">
                                    {{ $message ?? __('translation.messages.page_not_found_message') }}
                                </p>
                                <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-house-door me-1"></i> {{ __('translation.messages.go_home') }}
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- Footer -->
                    <p class="text-center text-muted mt-4 mb-0">
                        {{ __('translation.layout.home.footer.rights', ['year' => date('Y')]) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
    @push('scripts')
        @include('modules.i18n')
        <script>

        </script>
    @endpush
