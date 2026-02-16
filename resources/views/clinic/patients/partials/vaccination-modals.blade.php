{{-- New Vaccination Modal --}}
<div class="modal fade" id="newVaccinationModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('patients.vaccinations.store', $patient) }}" method="POST" class="add-vaccination-form">
                @csrf
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-syringe me-2"></i>{{ __('translation.add_vaccination') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Tabs Navigation --}}
            <ul class="nav nav-tabs px-3 pt-3 border-bottom-0" id="vaccinationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="manual-vaccination-tab" data-bs-toggle="tab" data-bs-target="#manualVaccinationPane" type="button" role="tab" aria-controls="manualVaccinationPane" aria-selected="true">
                        <i class="fas fa-edit me-1"></i>{{ __('translation.examination.manual_entry') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ai-vaccination-tab" data-bs-toggle="tab" data-bs-target="#aiVaccinationPane" type="button" role="tab" aria-controls="aiVaccinationPane" aria-selected="false">
                        <i class="fas fa-robot me-1"></i>{{ __('translation.ai_vaccination') }}
                        <span class="badge bg-info ms-1">{{ __('translation.examination.coming_soon') }}</span>
                    </button>
                </li>
            </ul>

            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <div class="tab-content" id="vaccinationTabContent">
                {{-- Tab 1: Manual Vaccination Form --}}
                <div class="tab-pane fade show active" id="manualVaccinationPane" role="tabpanel" aria-labelledby="manual-vaccination-tab">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.vaccination_type') }} <span class="text-danger">*</span></label>
                        <select class="form-select select2" id="vaccinationType" name="vaccination_type_id" required>
                            <option value="">{{ __('translation.select_vaccination') }}</option>
                            @foreach(\App\Models\VaccinationType::with('translations')->where('is_active', true)->orderBy('order')->get() as $type)
                                <option value="{{ $type->id }}" 
                                    data-disease="{{ $type->disease_prevented }}"
                                    data-doses="{{ $type->doses_required }}">
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.vaccination_date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="vaccinationDate" name="vaccination_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.dose_number') }}</label>
                        <input type="number" class="form-control" id="doseNumber" name="dose_number" value="1" min="1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.batch_number') }}</label>
                        <input type="text" class="form-control" id="batchNumber" name="batch_number">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.manufacturer') }}</label>
                        <input type="text" class="form-control" id="manufacturer" name="manufacturer">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.injection_site') }}</label>
                        <select class="form-select choices-select" id="injectionSite" name="site">
                            <option value="">{{ __('translation.select_site') }}</option>
                            <option value="Left Arm">{{ __('translation.left_arm') }}</option>
                            <option value="Right Arm">{{ __('translation.right_arm') }}</option>
                            <option value="Left Thigh">{{ __('translation.left_thigh') }}</option>
                            <option value="Right Thigh">{{ __('translation.right_thigh') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.next_dose_date') }}</label>
                        <input type="date" class="form-control" id="nextDoseDate" name="next_dose_due_date">
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.reaction_notes') }}</label>
                        <textarea class="form-control" id="reactionNotes" name="reaction_notes" rows="2" placeholder="{{ __('translation.any_adverse_reactions') }}"></textarea>
                    </div>
                </div>
                </div>{{-- end manualVaccinationPane --}}

                {{-- Tab 2: AI (Coming Soon) --}}
                <div class="tab-pane fade" id="aiVaccinationPane" role="tabpanel" aria-labelledby="ai-vaccination-tab">
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-robot text-info" style="font-size: 5rem; opacity: 0.3;"></i>
                        </div>
                        <h4 class="text-muted mb-3">{{ __('translation.ai_vaccination') }}</h4>
                        <p class="text-muted mb-4 px-5">{{ __('translation.ai_vaccination_description') }}</p>
                        <span class="badge bg-info fs-6 px-4 py-2">
                            <i class="fas fa-clock me-2"></i>{{ __('translation.examination.coming_soon') }}
                        </span>
                    </div>
                </div>{{-- end aiVaccinationPane --}}

                </div>{{-- end tab-content --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="submit" id="vaccinationSaveBtn" class="btn btn-danger">
                    <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- View Vaccination Details Modal --}}
<div class="modal fade" id="viewVaccinationModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-syringe me-2"></i>{{ __('translation.vaccination_details') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="vaccinationDetailsContent" style="max-height: 50vh; overflow-y: auto;">
                <div class="text-center py-4">
                    <div class="spinner-border text-danger" role="status">
                        <span class="visually-hidden">{{ __('translation.common.loading') }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.close') }}</button>
            </div>
        </div>
    </div>
</div>
