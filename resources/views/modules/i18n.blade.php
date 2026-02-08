@php
    $i18n_global = [
        'base' => [
            'BASE_URL' => url('/'),
            'CSRF_TOKEN' => csrf_token(),
            'APP_LOCALE' => app()->getLocale(),
        ],

        'messages' => [
            'success' => __('translation.messages.success'),
            'added_successfully' => __('translation.messages.added_successfully'),
            'are_you_sure' => __('translation.messages.are_you_sure'),
            'you_wont_be_able_to_revert_this' => __('translation.messages.you_wont_be_able_to_revert_this'),
            'cancel' => __('translation.messages.cancel'),
            'error' => __('translation.messages.error'),
            'confirm' => __('translation.messages.confirm'),
            'enter_comments' => __('translation.messages.enter_comments'),
            'please_enter_comments' => __('translation.messages.please_enter_comments'),
            'comments' => __('translation.messages.comments'),
            'pleaseFillAllRequiredFields' => __('translation.messages.pleaseFillAllRequiredFields'),
            'select_an_option' => __('translation.messages.select_an_option'),
            'processing' => __('translation.messages.processing'),
            'operation_completed_successfully' => __('translation.messages.operation_completed_successfully'),
            'operation_failed' => __('translation.messages.operation_failed'),
            'an_error_occurred' => __('translation.messages.an_error_occurred'),
            'invalid_server_response' => __('translation.messages.invalid_server_response'),
            'unexpected_response' => __('translation.messages.unexpected_response'),
            'request_timeout' => __('translation.messages.request_timeout'),
            'not_authorized' => __('translation.messages.not_authorized'),
            'table_copy_success' => __('translation.messages.table_copy_success'),
            'table_copy_failed' => __('translation.messages.table_copy_failed'),
            'table_export_success' => __('translation.messages.table_export_success'),
            'print_dialog_opened' => __('translation.messages.print_dialog_opened'),
            'no_data_to_display' => __('translation.messages.no_data_to_display'),
            'generated_on' => __('translation.messages.generated_on'),
            'data_export' => __('translation.messages.data_export'),
            'session_expired' => __('translation.messages.session_expired'),
            'session_expired_redirecting' => __('translation.messages.session_expired_redirecting'),
            'confirm_status_change_title' => __('translation.messages.confirm_status_change_title'),
            'change_status_to_text' => __('translation.messages.change_status_to_text'),
            // Patient duplicate detection
            'duplicate_warning' => __('translation.patient.duplicate_warning'),
            'duplicate_match_name' => __('translation.patient.duplicate_match_name'),
            'duplicate_match_phone' => __('translation.patient.duplicate_match_phone'),
            'duplicate_match_email' => __('translation.patient.duplicate_match_email'),
            'duplicate_confidence' => __('translation.patient.duplicate_confidence'),
            'view_existing' => __('translation.patient.view_existing'),
            'create_anyway' => __('translation.patient.create_anyway'),
        ],

        'datatable' => [
            'search' => __('translation.datatable.search'),
            'lengthMenu' => __('translation.datatable.lengthMenu'),
            'info' => __('translation.datatable.info'),
            'processing' => __('translation.datatable.processing'),
            'emptyTable' => __('translation.datatable.emptyTable'),
            'zeroRecords' => __('translation.datatable.zeroRecords'),
        ],

        'confirm' => [
            'message' => __('translation.modal.confirm_delete.message_with_item'),
            'activate_message' => __('translation.modal.confirm_activate.message'),
        ],

        'toasts' => [
            'delete_success' => __('translation.js.toasts.delete_success'),
            'delete_failed' => __('translation.js.toasts.delete_failed'),
            'activate_success' => __('translation.js.toasts.activate_success'),
            'activate_failed' => __('translation.js.toasts.activate_failed'),
        ],
        'models' => [
            'cash_vault' => __('translation.models.cash_vault'),
            'client_transaction' => __('translation.models.client_transaction'),
        ],
    ];

    $page = $page ?? '';

    switch ($page) {
        case 'configurations':
            $i18n_page = [
                'table' => [
                    'id' => __('translation.configurations.table.headers.id'),
                    'name' => __('translation.configurations.table.headers.name'),
                    'score' => __('translation.configurations.table.headers.score'),
                    'page' => __('translation.configurations.table.headers.page'),
                    'key' => __('translation.configurations.table.headers.key'),
                    'actions' => __('translation.configurations.table.headers.actions'),
                ],
                'form' => [
                    'title_edit' => __('translation.configurations.form.modal.title_edit'),
                    'title_add' => __('translation.configurations.form.modal.title_add'),
                    'btn_update' => __('translation.configurations.form.buttons.update'),
                    'btn_save' => __('translation.configurations.form.buttons.save'),
                ],
                'labels' => [
                    'id' => __('translation.configurations.labels.id'),
                    'name' => __('translation.configurations.labels.name'),
                    'score' => __('translation.configurations.labels.score'),
                    'page' => __('translation.configurations.labels.page'),
                    'key' => __('translation.configurations.labels.key'),
                    'created_at' => __('translation.configurations.labels.created_at'),
                ],
            ];
            break;
             case 'images':
            $i18n_page = [
                 'table' => [
               'id' => __('translation.images.table.headers.id'),
               'name' => __('translation.images.table.headers.name'),
               'roles' => __('translation.images.table.headers.key'),
               'page' => __('translation.images.table.headers.page'),
               'actions' => __('translation.images.table.headers.actions'),
           ],
           'labels' => [
                 'id' => __('translation.images.table.headers.id'),
               'name' => __('translation.images.table.headers.name'),
               'roles' => __('translation.images.table.headers.key'),
               'page' => __('translation.images.table.headers.page'),
               'actions' => __('translation.images.table.headers.actions'),
           ],
           'form' => [
               'title_edit' => __('translation.images.form.modal.title_edit'),
               'title_add' => __('translation.images.form.modal.title_add'),
               'btn_update' => __('translation.images.form.buttons.update'),
               'btn_save' => __('translation.images.form.buttons.save'),
           ],
            ];
            break;
case 'links':
            $i18n_page = [
                 'table' => [
               'id' => __('translation.links.table.headers.id'),
               'name' => __('translation.links.table.headers.name'),
               'roles' => __('translation.links.table.headers.key'),
               'page' => __('translation.links.table.headers.page'),
               'actions' => __('translation.links.table.headers.actions'),
           ],
           'labels' => [
                 'id' => __('translation.links.table.headers.id'),
               'name' => __('translation.links.table.headers.name'),
               'roles' => __('translation.links.table.headers.key'),
               'page' => __('translation.links.table.headers.page'),
               'actions' => __('translation.links.table.headers.actions'),
           ],
           'form' => [
               'title_edit' => __('translation.links.form.modal.title_edit'),
               'title_add' => __('translation.links.form.modal.title_add'),
               'btn_update' => __('translation.links.form.buttons.update'),
               'btn_save' => __('translation.links.form.buttons.save'),
           ],
            ];
            break;

            case 'countries':
            $i18n_page = [
                 'table' => [
               'id' => __('translation.countries.table.headers.id'),
               'name' => __('translation.countries.table.headers.name'),
               'phone_extension' => __('translation.countries.table.headers.phone_extension'),
               'code' => __('translation.countries.table.headers.code'),
               'actions' => __('translation.countries.table.headers.actions'),
           ],
           'labels' => [
               'id' => __('translation.countries.table.headers.id'),
               'name' => __('translation.countries.table.headers.name'),
               'phone_extension' => __('translation.countries.table.headers.phone_extension'),
               'code' => __('translation.countries.table.headers.code'),
               'created_at' => __('translation.countries.table.headers.created_at'),
               'actions' => __('translation.countries.table.headers.actions'),
           ],
           'form' => [
               'title_edit' => __('translation.countries.form.modal.title_edit'),
               'title_add' => __('translation.countries.form.modal.title_add'),
               'btn_update' => __('translation.countries.form.buttons.update'),
               'btn_save' => __('translation.countries.form.buttons.save'),
           ],
            ];
            break;
            case 'roles':
            $i18n_page = [
                'table' => [
               'id' => __('translation.roles.table.headers.id'),
               'name' => __('translation.roles.table.headers.name'),
               'roles' => __('translation.roles.table.headers.roles'),
               'page' => __('translation.roles.table.headers.page'),
               'actions' => __('translation.roles.table.headers.actions'),
           ],
           'labels' => [
                'id' => __('translation.roles.table.headers.id'),
               'name' => __('translation.roles.table.headers.name'),
               'roles' => __('translation.roles.table.headers.roles'),
               'page' => __('translation.roles.table.headers.page'),
               'actions' => __('translation.roles.table.headers.actions'),
           ],
           'form' => [
               'title_edit' => __('translation.roles.form.modal.title_edit'),
               'title_add' => __('translation.roles.form.modal.title_add'),
               'btn_update' => __('translation.roles.form.buttons.update'),
               'btn_save' => __('translation.roles.form.buttons.save'),
           ],
            ];
            break;

            case 'currencies':
            $i18n_page = [
                'table' => [
                    'id' => __('translation.currencies.table.headers.id'),
                    'name' => __('translation.currencies.table.headers.name'),
                    'code' => __('translation.currencies.table.headers.code'),
                    'base_currency' => __('translation.currencies.table.headers.base_currency'),
                    'buy_rate' => __('translation.currencies.table.headers.buy_rate'),
                    'sell_rate' => __('translation.currencies.table.headers.sell_rate'),
                    'created_at' => __('translation.currencies.table.headers.created_at'),
                    'updated_at' => __('translation.currencies.table.headers.updated_at'),
                    'actions' => __('translation.currencies.table.headers.actions'),
                ],
                'labels' => [
                    'id' => __('translation.currencies.table.headers.id'),
                    'name' => __('translation.currencies.table.headers.name'),
                    'code' => __('translation.currencies.table.headers.code'),
                    'base_currency' => __('translation.currencies.table.headers.base_currency'),
                    'buy_rate' => __('translation.currencies.table.headers.buy_rate'),
                    'sell_rate' => __('translation.currencies.table.headers.sell_rate'),
                    'updated_by' => __('translation.currencies.form.fields.updated_by'),
                    'created_at' => __('translation.currencies.table.headers.created_at'),
                    'updated_at' => __('translation.currencies.table.headers.updated_at'),
                    'actions' => __('translation.currencies.table.headers.actions'),
                ],
           'form' => [
               'title_edit' => __('translation.currencies.form.modal.title_edit'),
               'title_add' => __('translation.currencies.form.modal.title_add'),
               'btn_update' => __('translation.currencies.form.buttons.update'),
               'btn_save' => __('translation.currencies.form.buttons.save'),
           ],
            ];
            break;


            case 'cash_vaults':
            $i18n_page = [
                'table' => [
                    'id' => __('translation.cash_vaults.table.headers.id'),
                    'type' => __('translation.cash_vaults.table.headers.type'),
                    'transaction_type' => __('translation.cash_vaults.table.headers.transaction_type'),
                    'currency' => __('translation.cash_vaults.table.headers.currency'),
                    'amount' => __('translation.cash_vaults.table.headers.amount'),
                    'user' => __('translation.cash_vaults.table.headers.user'),
                    'note' => __('translation.cash_vaults.table.headers.note'),
                    'created_at' => __('translation.cash_vaults.table.headers.created_at'),
                    'actions' => __('translation.cash_vaults.table.headers.actions'),
                ],
                'labels' => [
                    'id' => __('translation.cash_vaults.table.headers.id'),
                    'type' => __('translation.cash_vaults.table.headers.type'),
                    'transaction_type' => __('translation.cash_vaults.table.headers.transaction_type'),
                    'currency' => __('translation.cash_vaults.table.headers.currency'),
                    'types' => [
                        'buy' => __('translation.cash_vaults.types.buy'),
                        'sell' => __('translation.cash_vaults.types.sell'),
                        'deposit' => __('translation.cash_vaults.types.deposit'),
                        'withdraw' => __('translation.cash_vaults.types.withdraw'),

                    ],
                    'transaction_types' => [
                        'deposit' => __('translation.cash_vaults.transaction_types.deposit'),
                        'withdraw' => __('translation.cash_vaults.transaction_types.withdraw'),
                    ],
                    'foreign_amount' => __('translation.cash_vaults.purchases.foreign_amount'),
                    'base_currency_amount' => __('translation.cash_vaults.purchases.base_currency_amount'),
                    'rate' => __('translation.cash_vaults.purchases.rate'),
                    'amount' => __('translation.cash_vaults.table.headers.amount'),
                    'user' => __('translation.cash_vaults.table.headers.user'),
                    'note' => __('translation.cash_vaults.table.headers.note'),
                    'created_at' => __('translation.cash_vaults.table.headers.created_at'),
                    'actions' => __('translation.cash_vaults.table.headers.actions'),
                    'client' => __('translation.cash_vaults.purchases.form.fields.client'),
                ],
               'form' => [
                   'title_add' => __('translation.cash_vaults.purchases.form.title_add'),
                   'title_edit' => __('translation.cash_vaults.purchases.form.title_add'),
                   'btn_save' => __('translation.cash_vaults.purchases.form.buttons.save'),
                   'btn_update' => __('translation.cash_vaults.purchases.form.buttons.save'),
                   'fields' => [
                       'client' => __('translation.cash_vaults.purchases.form.fields.client'),
                       'foreign_amount' => __('translation.cash_vaults.purchases.form.fields.foreign_amount'),
                       'rate' => __('translation.cash_vaults.purchases.form.fields.rate'),
                       'base_currency_amount' => __('translation.cash_vaults.purchases.form.fields.base_currency_amount'),
                   ],
                   'placeholders' => [
                       'client_search' => __('translation.cash_vaults.purchases.form.placeholders.client_search'),
                   ],
                   'purchase' => [
                       'title_add' => __('translation.cash_vaults.purchases.form.title_add'),
                       'title_edit' => __('translation.cash_vaults.purchases.form.title_edit'),
                       'foreign_amount' => __('translation.cash_vaults.purchases.foreign_amount'),
                      'base_currency_amount' => __('translation.cash_vaults.purchases.base_currency_amount'),
                       'rate' => __('translation.cash_vaults.table.headers.rate'),
                   ],
                   'sales' => [
                       'title_add' => __('translation.cash_vaults.sales.form.title_add'),
                       'title_edit' => __('translation.cash_vaults.sales.form.title_edit'),
                       'foreign_amount' => __('translation.cash_vaults.sales.foreign_amount'),
                      'base_currency_amount' => __('translation.cash_vaults.sales.base_currency_amount'),
                       'rate' => __('translation.cash_vaults.table.headers.rate'),
                   ],

               ],
               // labels for audit/details keys so frontend can show translated field names
               'details_labels' => [
                   'transaction' => __('translation.cash_vaults.table.headers.transaction_type'),
                   'currency_id' => __('translation.cash_vaults.table.headers.currency'),
                   'amount' => __('translation.cash_vaults.table.headers.amount'),
                   'rate' => __('translation.cash_vaults.table.headers.rate'),
                   'note' => __('translation.cash_vaults.table.headers.note'),
                   'client_id' => __('translation.cash_vaults.purchases.form.fields.client'),
               ],
            ];
            break;
            case 'partner_companies':
            $i18n_page = [
                 'table' => [

               'id' => __('translation.partner_companies.table.headers.id'),
               'name' => __('translation.partner_companies.table.headers.name'),
               'phone' => __('translation.partner_companies.table.headers.phone'),
               'email' => __('translation.partner_companies.table.headers.email'),
               'actions' => __('translation.partner_companies.table.headers.actions'),

                 'commercial_registration_number' => __('translation.partner_companies.table.headers.commercial_registration_number'),
               'license_number' => __('translation.partner_companies.table.headers.license_number'),
               'status' => __('translation.partner_companies.table.headers.status'),
               'created_at' => __('translation.partner_companies.table.headers.created_at'),
                 'updated_at' => __('translation.partner_companies.table.headers.updated_at'),
           ],
           'labels' => [
             'id' => __('translation.partner_companies.table.headers.id'),
               'name' => __('translation.partner_companies.table.headers.name'),
               'phone' => __('translation.partner_companies.table.headers.phone'),
               'email' => __('translation.partner_companies.table.headers.email'),
               'actions' => __('translation.partner_companies.table.headers.actions'),

                 'commercial_registration_number' => __('translation.partner_companies.table.headers.commercial_registration_number'),
               'license_number' => __('translation.partner_companies.table.headers.license_number'),
               'status' => __('translation.partner_companies.table.headers.status'),
               'created_at' => __('translation.partner_companies.table.headers.created_at'),
                 'updated_at' => __('translation.partner_companies.table.headers.updated_at'),
           ],
           'form' => [
               'title_edit' => __('translation.partner_companies.form.modal.title_edit'),
               'title_add' => __('translation.partner_companies.form.modal.title_add'),
               'btn_update' => __('translation.partner_companies.form.buttons.update'),
               'btn_save' => __('translation.partner_companies.form.buttons.save'),
           ],
            ];
            break;

            case 'clients':
            $i18n_page = [
                 'table' => [
               'id' => __('translation.clients.table.headers.id'),
               'name' => __('translation.clients.table.headers.name'),
               'phone' => __('translation.clients.table.headers.phone'),
               'national_id' => __('translation.clients.table.headers.national_id'),
               'address' => __('translation.clients.table.headers.address'),
'actions' => __('translation.clients.table.headers.actions'),
               'created_at' => __('translation.clients.table.headers.created_at'),
                 'updated_at' => __('translation.clients.table.headers.updated_at'),
           ],
           'labels' => [
           'id' => __('translation.clients.table.headers.id'),
               'name' => __('translation.clients.table.headers.name'),
               'phone' => __('translation.clients.table.headers.phone'),
               'national_id' => __('translation.clients.table.headers.national_id'),
               'address' => __('translation.clients.table.headers.address'),
'actions' => __('translation.clients.table.headers.actions'),
               'created_at' => __('translation.clients.table.headers.created_at'),
                 'updated_at' => __('translation.clients.table.headers.updated_at'),
           ],
           'form' => [
               'title_edit' => __('translation.clients.form.modal.title_edit'),
               'title_add' => __('translation.clients.form.modal.title_add'),
               'btn_update' => __('translation.clients.form.buttons.update'),
               'btn_save' => __('translation.clients.form.buttons.save'),
           ],
            ];
            break;

            case 'client_deposits':
            $i18n_page = [
                 'table' => [

               'id' => __('translation.client_deposits.table.headers.id'),
               'client' => __('translation.client_deposits.table.headers.client'),
               'currency' => __('translation.client_deposits.table.headers.currency'),
               'monthly_profit_rate' => __('translation.client_deposits.table.headers.monthly_profit_rate'),
               'monthly_profit_hint' => __('translation.client_deposits.table.headers.monthly_profit_hint'),
                'opening_date' => __('translation.client_deposits.table.headers.opening_date'),
                 'status' => __('translation.client_deposits.table.headers.status'),
                  'status_active' => __('translation.client_deposits.table.headers.status_active'),
'user' => __('translation.client_deposits.table.headers.user'),
'note' => __('translation.client_deposits.table.headers.note'),

'actions' => __('translation.client_deposits.table.headers.actions'),
               'created_at' => __('translation.client_deposits.table.headers.created_at'),
                 'updated_at' => __('translation.client_deposits.table.headers.updated_at'),
           ],
           'labels' => [

               'id' => __('translation.client_deposits.table.headers.id'),
               'client' => __('translation.client_deposits.table.headers.client'),
               'currency' => __('translation.client_deposits.table.headers.currency'),
               'monthly_profit_rate' => __('translation.client_deposits.table.headers.monthly_profit_rate'),
               'monthly_profit_hint' => __('translation.client_deposits.table.headers.monthly_profit_hint'),
                'opening_date' => __('translation.client_deposits.table.headers.opening_date'),
                 'status' => __('translation.client_deposits.table.headers.status'),
                  'status_active' => __('translation.client_deposits.table.headers.status_active'),
'user' => __('translation.client_deposits.table.headers.user'),
'note' => __('translation.client_deposits.table.headers.note'),

'actions' => __('translation.client_deposits.table.headers.actions'),
               'created_at' => __('translation.client_deposits.table.headers.created_at'),
                 'updated_at' => __('translation.client_deposits.table.headers.updated_at'),
           ],
           'form' => [
               'title_edit' => __('translation.client_deposits.form.modal.title_edit'),
               'title_add' => __('translation.client_deposits.form.modal.title_add'),
               'btn_update' => __('translation.client_deposits.form.buttons.update'),
               'btn_save' => __('translation.client_deposits.form.buttons.save'),
               'withdraw' =>[
                'title_edit' => __('translation.client_withdrawals.edit_withdrawal'),
               'title_add' => __('translation.client_withdrawals.add_withdrawal'),
               
                ]
           ],
            ];
            break;
        case 'specialties':
            $i18n_page = [
                'table' => [
                    'id' => __('translation.specialties.labels.id'),
                    'name' => __('translation.specialties.name'),
                    'key' => __('translation.specialties.key'),
                    'icon' => __('translation.specialties.icon'),
                    'color' => __('translation.specialties.color'),
                    'sort_order' => __('translation.specialties.sort_order'),
                    'status' => __('translation.specialties.status'),
                    'actions' => __('translation.specialties.actions'),
                    'created_at' => __('translation.specialties.labels.created_at'),
                ],
                'labels' => [
                    'id' => __('translation.specialties.labels.id'),
                    'name' => __('translation.specialties.labels.name'),
                    'key' => __('translation.specialties.labels.key'),
                    'description' => __('translation.specialties.labels.description'),
                    'icon' => __('translation.specialties.labels.icon'),
                    'color' => __('translation.specialties.labels.color'),
                    'sort_order' => __('translation.specialties.labels.sort_order'),
                    'status' => __('translation.specialties.labels.status'),
                    'active' => __('translation.specialties.labels.active'),
                    'inactive' => __('translation.specialties.labels.inactive'),
                    'created_at' => __('translation.specialties.labels.created_at'),
                ],
                'form' => [
                    'title_edit' => __('translation.specialties.form.title_edit'),
                    'title_add' => __('translation.specialties.form.title_add'),
                    'btn_update' => __('translation.specialties.form.btn_update'),
                    'btn_save' => __('translation.specialties.form.btn_save'),
                ],
            ];
            break;
        case 'topics':
            $i18n_page = [
                'table' => [
                    'id' => __('translation.topics.labels.id'),
                    'name' => __('translation.topics.name'),
                    'description' => __('translation.topics.description'),
                    'icon' => __('translation.topics.icon'),
                    'sort_order' => __('translation.topics.sort_order'),
                    'status' => __('translation.topics.status'),
                    'actions' => __('translation.topics.actions'),
                ],
                'labels' => [
                    'id' => __('translation.topics.labels.id'),
                    'name' => __('translation.topics.labels.name'),
                    'description' => __('translation.topics.labels.description'),
                    'icon' => __('translation.topics.labels.icon'),
                    'sort_order' => __('translation.topics.labels.sort_order'),
                    'status' => __('translation.topics.labels.status'),
                    'active' => __('translation.topics.labels.active'),
                    'inactive' => __('translation.topics.labels.inactive'),
                ],
                'form' => [
                    'title_edit' => __('translation.topics.form.title_edit'),
                    'title_add' => __('translation.topics.form.title_add'),
                    'btn_update' => __('translation.topics.form.btn_update'),
                    'btn_save' => __('translation.topics.form.btn_save'),
                ],
            ];
            break;
        case 'subscription_features':
            $i18n_page = [
                'table' => [
                    'id' => __('translation.subscription_features.feature_text'),
                    'feature_text' => __('translation.subscription_features.feature_text'),
                    'icon' => __('translation.subscription_features.icon'),
                    'highlighted' => __('translation.subscription_features.highlighted'),
                    'sort_order' => __('translation.subscription_features.sort_order'),
                    'status' => __('translation.subscription_features.status'),
                    'actions' => __('translation.subscription_features.actions'),
                ],
                'labels' => [
                    'feature_text' => __('translation.subscription_features.feature_text'),
                    'icon' => __('translation.subscription_features.icon'),
                    'highlighted' => __('translation.subscription_features.highlighted'),
                    'sort_order' => __('translation.subscription_features.sort_order'),
                    'status' => __('translation.subscription_features.status'),
                    'active' => __('translation.subscription_features.active'),
                    'inactive' => __('translation.subscription_features.inactive'),
                ],
                'form' => [
                    'title_edit' => __('translation.subscription_features.form.title_edit'),
                    'title_add' => __('translation.subscription_features.form.title_add'),
                    'btn_update' => __('translation.subscription_features.form.btn_update'),
                    'btn_save' => __('translation.subscription_features.form.btn_save'),
                ],
            ];
            break;
        default:
            $i18n_page = [];
    }
@endphp

@push('i18n')
    <script>
        // Combine global + page-specific translations
        window.i18n = Object.assign({}, @json($i18n_global), @json($i18n_page));
    </script>
@endpush
