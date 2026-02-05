@extends('layout.dashboard.main')
@section('content')
    <section class="section">
        @can(\App\Enums\PermissionEnum::SETTING_ADD)
            <div class="pt-2">
                <button
                    class="edit btn btn-xs btn-primary mb-4"
                    href="javascript:" onclick="openAdd()">
                    <i class="bi bi-plus-square"></i>
                </button>
            </div>
        @endcan

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('translation.team_members.title') }}</h5>
                        <form id="search" class="">
                            <div class="row">

                            </div>
                        </form>
                        <table class="table table-bordered table-striped w-100" id="team_members">
                            <thead>
                            <tr>
                                <th>{{ __('translation.team_members.table.headers.id') }}</th>
                                <th>{{ __('translation.team_members.table.headers.name') }}</th>
                                <th>{{ __('translation.team_members.table.headers.title') }}</th>
                                <th>{{ __('translation.team_members.table.headers.linkedin') }}</th>
                                <th>{{ __('translation.team_members.table.headers.image') }}</th>
                                <th>{{ __('translation.team_members.table.headers.active') }}</th>
                                <th>{{ __('translation.team_members.table.headers.actions') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @component('dashboard.about.team_members.form')
    @endcomponent
    @component('dashboard.about.team_members.view')
    @endcomponent

@stop
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const t = {
                fields: {
                    name: @json(__('translation.team_members.fields.name')),
                    title: @json(__('translation.team_members.fields.title')),
                    linkedin: @json(__('translation.team_members.fields.linkedin')),
                    image: @json(__('translation.team_members.fields.image')),
                    active: @json(__('translation.team_members.fields.active')),
                },
                actions: {
                    viewImage: @json(__('translation.team_members.actions.view_image')),
                },
                general: {
                    add: @json(__('translation.general.add')),
                    edit: @json(__('translation.general.edit')),
                    update: @json(__('translation.general.update')),
                    yes: @json(__('translation.general.yes')),
                    no: @json(__('translation.general.no')),
                    image: @json(__('translation.general.image')),
                },
                confirmDelete: @json(__('translation.modal.confirm_delete.message')),
            };

            let table = null;

            if (window.DataTable) {
                try {
                    table = new window.DataTable('#team_members', {
                        processing: true,
                        serverSide: true,
                        language: { url: '{{$language}}' },
                        ajax: '{!! route('team_members.index') !!}',
                        responsive: true,
                        lengthMenu: [[10, 25, 50, 100, 1000, 10000, -1], [10, 25, 50, 100, 1000, 10000]],
                        buttons: ['csvHtml5'],
                        columns: [
                            { data: 'id' },
                            { data: 'name', name: 'translations.name', orderable: false, render: (data) => data ? data : '' },
                            { data: 'title', name: 'translations.title', orderable: false, render: (data) => data ? data : '' },
                            { data: 'linkedin', orderable: false, render: (data) => data ? `<a target="_blank" href="${data}">${t.fields.linkedin}</a>` : '' },
                            { data: 'image', render: (data) => data ? `<a target="_blank" href="{{ Storage::url('${data}') }}">${t.general.image}</a>` : '' },
                            {
                                data: 'active',
                                render: (data, _type, row) => {
                                    const switchId = 'activeSwitch_' + row.id;
                                    const checked = (data == 1) ? 'checked' : '';
                                    return `<div class="form-check form-switch"><input class="form-check-input team-active" type="checkbox" id="${switchId}" ${checked}><label class="form-check-label" for="${switchId}"></label></div>`;
                                }
                            },
                            { data: 'action', name: 'action', orderable: false, searchable: false }
                        ]
                    });
                } catch (e) {
                    console.warn('Team members DataTable init failed:', e);
                }
            }

            // Handle row actions via event delegation
            const tableEl = document.getElementById('team_members');
            tableEl?.addEventListener('click', function (e) {
                const viewBtn = e.target.closest('.view');
                const editBtn = e.target.closest('.edit');
                const deleteBtn = e.target.closest('.delete');
                if (!viewBtn && !editBtn && !deleteBtn) return;

                const tr = e.target.closest('tr');
                if (!tr || !table) return;
                const data = table.row(tr).data();
                if (!data) return;

                if (viewBtn) {
                    window.openView(data);
                } else if (editBtn) {
                    window.openEdit(data);
                } else if (deleteBtn) {
                    // Basic confirm + navigate delete endpoint
                    if (confirm(t.confirmDelete || (window.i18n && window.i18n.messages && window.i18n.messages.are_you_sure) || '')) {
                        fetch(`dashboard/team_members/${data.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': (window.Utils && window.Utils.getCSRFToken) ? window.Utils.getCSRFToken() : '',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        }).then(() => {
                            try { table.ajax.reload(); } catch (_) { window.location.reload(); }
                        }).catch(() => window.location.reload());
                    }
                }
            });

            tableEl?.addEventListener('change', function (e) {
                const toggle = e.target.closest('.team-active');
                if (!toggle || !table) return;
                const tr = e.target.closest('tr');
                if (!tr) return;
                const data = table.row(tr).data();
                if (!data) return;
                const isActive = !!toggle.checked;

                fetch(`dashboard/team_members/${data.id}/updateActiveStatus`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': (window.Utils && window.Utils.getCSRFToken) ? window.Utils.getCSRFToken() : '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ active: isActive })
                }).then(() => {
                    try { table.ajax.reload(null, false); } catch (_) { /* no-op */ }
                }).catch(() => { /* no-op */ });
            });

            // Column selector (optional) - use Choices if present
            const columnSelector = document.getElementById('columnSelector');
            if (columnSelector && window.loadChoices) {
                window.loadChoices().then((Choices) => {
                    if (!columnSelector._choices) {
                        columnSelector._choices = new Choices(columnSelector, { removeItemButton: true, shouldSort: false });
                    }
                }).catch(() => null);

                columnSelector.addEventListener('change', function () {
                    if (!table) return;
                    const selected = Array.from(columnSelector.selectedOptions).map(o => parseInt(o.value, 10)).filter(Number.isFinite);
                    table.columns().visible(false);
                    selected.forEach((idx) => {
                        table.column(idx).visible(true);
                    });
                    table.draw();
                });
            }
        });

        openView = (data) => {
            const modal = document.getElementById('view-modal');
            const dataEl = document.getElementById('data');
            if (!modal || !dataEl) return;

            const fields = [
                { label: t.fields.name, value: removeHtmlTags(data.name ?? '') },
                { label: t.fields.title, value: removeHtmlTags(data.title ?? '') },

                // Social links
                { label: t.fields.linkedin, value: data.linkedin ? `<a target="_blank" href="${data.linkedin}">${t.fields.linkedin}</a>` : '' },

                // Image link
                { label: t.fields.image, value: data.image ? `<a target="_blank" href="{{ Storage::url('${data.image}') }}">${t.actions.viewImage}</a>` : '' },

                // Active status
                {
                    label: t.fields.active,
                    value: data.active == 1
                        ? `<span class="text-success">${t.general.yes}</span>`
                        : `<span class="text-danger">${t.general.no}</span>`
                }
            ];

            const fieldHtml = fields.map(field => `
        <div class="form-row">
            <div class="form-group col-md-3">
                <label class="required-label">${field.label}:</label>
            </div>
            <div class="form-group col-md-9">
                <span>${field.value}</span>
            </div>
        </div>
    `).join('');

            dataEl.innerHTML = fieldHtml;
            if (window.clearErrors) window.clearErrors(modal);
            if (window.showModal) window.showModal(modal);
        };


        openAdd = () => {
            const modal = document.getElementById('add-modal');
            const form = document.querySelector('.add-form');
            if (!modal || !form) return;
            form.reset();
            form.setAttribute('action', `dashboard/team_members`);
            form.querySelector('input[name="_method"]')?.remove();
            modal.querySelector('input[type="hidden"][name="id"]')?.setAttribute('value', '');
            modal.querySelector('.modal-title')?.textContent = t.general.add;
            const imageEl = document.getElementById('image');
            if (imageEl) imageEl.innerHTML = t.general.image;
            if (window.clearErrors) window.clearErrors(modal);
            if (window.showModal) window.showModal(modal);
        };

        openEdit = (data) => {
            const modal = document.getElementById('add-modal');
            const form = document.querySelector('.add-form');
            const dataWrap = document.querySelector('.data');
            if (!modal || !form) return;
            form.reset();
            if (!form.querySelector('input[name="_method"]')) {
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'PUT';
                form.appendChild(method);
            }
            form.setAttribute('action', `dashboard/team_members/` + data.id);
            modal.querySelector('.modal-title')?.textContent = t.general.edit;
            modal.querySelector('#afm_btnSaveIt')?.textContent = t.general.update;
            if (window.clearErrors) window.clearErrors(modal);
            if (typeof window._fill === 'function') window._fill(dataWrap, data);

            const imageEl = document.getElementById('image');
            if (imageEl) {
                imageEl.innerHTML = data.image ? `<a target="_blank" href="{{ Storage::url('${data.image}') }}">${t.general.image}</a>` : '';
            }

            if (window.showModal) window.showModal(modal);
        };

        function stripHtml(html) {
            var doc = new DOMParser().parseFromString(html, 'text/html');
            return doc.body.textContent || "";
        }

        function removeHtmlTags(encodedText) {
            var parser = new DOMParser();
            var doc = parser.parseFromString(encodedText, 'text/html');
            var decodedText = doc.body.textContent || "";
            return decodedText;
        }
    </script>
@endpush
