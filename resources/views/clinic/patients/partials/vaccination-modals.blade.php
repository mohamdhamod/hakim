{{-- New Vaccination Modal --}}
<div class="modal fade" id="newVaccinationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-syringe me-2"></i>{{ __('translation.add_vaccination') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.vaccination_type') }} <span class="text-danger">*</span></label>
                        <select class="form-select select2" id="vaccinationType" required>
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
                        <input type="date" class="form-control" id="vaccinationDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.dose_number') }}</label>
                        <input type="number" class="form-control" id="doseNumber" value="1" min="1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.batch_number') }}</label>
                        <input type="text" class="form-control" id="batchNumber">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.manufacturer') }}</label>
                        <input type="text" class="form-control" id="manufacturer">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.injection_site') }}</label>
                        <select class="form-select" id="injectionSite">
                            <option value="">{{ __('translation.select_site') }}</option>
                            <option value="Left Arm">{{ __('translation.left_arm') }}</option>
                            <option value="Right Arm">{{ __('translation.right_arm') }}</option>
                            <option value="Left Thigh">{{ __('translation.left_thigh') }}</option>
                            <option value="Right Thigh">{{ __('translation.right_thigh') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.next_dose_date') }}</label>
                        <input type="date" class="form-control" id="nextDoseDate">
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.reaction_notes') }}</label>
                        <textarea class="form-control" id="reactionNotes" rows="2" placeholder="{{ __('translation.any_adverse_reactions') }}"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="button" class="btn btn-danger" onclick="saveVaccination()">
                    <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- View Vaccination Details Modal --}}
<div class="modal fade" id="viewVaccinationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-syringe me-2"></i>{{ __('translation.vaccination_details') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="vaccinationDetailsContent">
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
