@extends('layout.main')
@include('layout.extra_meta')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-start text-start mb-3">
            <div class="col-xxl-12 col-xl-12">
        <span class="badge bg-light text-dark shadow px-2 py-1 mb-2 fs-6">
            <i class="bi bi-people me-1"></i> {{ __('translation.users.page_title') }}
        </span>
                <p class="fs-6 text-muted mb-0">
                    {{ __('translation.users.page_description') }}
                </p>
            </div>
        </div>



        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">{{ __('translation.users.list') }}</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-2"></i> {{ __('translation.users.add') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(($users ?? null) && $users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('translation.users.name')  }}</th>
                                <th>{{ __('translation.users.phone')  }}</th>
                                <th>{{ __('translation.users.email')  }}</th>
                                <th>{{ __('translation.users.roles')  }}</th>
                                <th>{{ __('translation.users.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>#{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                                    <td>
                                        @can(\App\Enums\PermissionEnum::USERS_UPDATE)
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary"
                                               data-bs-toggle="tooltip"
                                               data-bs-placement="top"
                                               title="{{ __('translation.users.edit') }}"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endcan
                    @can(\App\Enums\PermissionEnum::USERS_DELETE)
                                                <!-- Delete button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-danger delete-btn"
                                                        data-model='@json($user)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.users.delete') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                        </div>
                        <h5 class="text-muted mb-0">{{ __('translation.users.empty') ?? 'No users found.' }}</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('modules.confirm')
    @include('modules.i18n', ['page' => 'users'])
    <script>
        const i18n = window.i18n;
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);
                    confirmDelete(data , `{{ route('users.index') }}/${data.id}` , i18n , data.full_name);
                });
            });
        });
    </script>
@endpush
