{{-- Social History Modal --}}
<div class="modal fade" id="socialHistoryModal" tabindex="-1" aria-labelledby="socialHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('clinic.patients.update-social-history', $patient) }}" method="POST" id="socialHistoryForm">
                @csrf
                @method('PATCH')
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="socialHistoryModalLabel">
                        <i class="fas fa-users text-success me-2"></i>
                        {{ __('translation.social_history.title') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-smoking text-muted me-1"></i>
                                {{ __('translation.social_history.smoking_status') }}
                            </label>
                            <select name="smoking_status" class="form-select">
                                <option value="">{{ __('translation.common.select') }}</option>
                                <option value="never" {{ $patient->smoking_status === 'never' ? 'selected' : '' }}>{{ __('translation.social_history.smoking_never') }}</option>
                                <option value="former" {{ $patient->smoking_status === 'former' ? 'selected' : '' }}>{{ __('translation.social_history.smoking_former') }}</option>
                                <option value="current" {{ $patient->smoking_status === 'current' ? 'selected' : '' }}>{{ __('translation.social_history.smoking_current') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-wine-glass text-muted me-1"></i>
                                {{ __('translation.social_history.alcohol_status') }}
                            </label>
                            <select name="alcohol_status" class="form-select">
                                <option value="">{{ __('translation.common.select') }}</option>
                                <option value="never" {{ $patient->alcohol_status === 'never' ? 'selected' : '' }}>{{ __('translation.social_history.alcohol_never') }}</option>
                                <option value="occasional" {{ $patient->alcohol_status === 'occasional' ? 'selected' : '' }}>{{ __('translation.social_history.alcohol_occasional') }}</option>
                                <option value="regular" {{ $patient->alcohol_status === 'regular' ? 'selected' : '' }}>{{ __('translation.social_history.alcohol_regular') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-briefcase text-muted me-1"></i>
                                {{ __('translation.social_history.occupation') }}
                            </label>
                            <input type="text" name="occupation" class="form-control" value="{{ $patient->occupation }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-ring text-muted me-1"></i>
                                {{ __('translation.social_history.marital_status') }}
                            </label>
                            <select name="marital_status" class="form-select">
                                <option value="">{{ __('translation.common.select') }}</option>
                                <option value="single" {{ $patient->marital_status === 'single' ? 'selected' : '' }}>{{ __('translation.social_history.marital_single') }}</option>
                                <option value="married" {{ $patient->marital_status === 'married' ? 'selected' : '' }}>{{ __('translation.social_history.marital_married') }}</option>
                                <option value="divorced" {{ $patient->marital_status === 'divorced' ? 'selected' : '' }}>{{ __('translation.social_history.marital_divorced') }}</option>
                                <option value="widowed" {{ $patient->marital_status === 'widowed' ? 'selected' : '' }}>{{ __('translation.social_history.marital_widowed') }}</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-comment-medical text-muted me-1"></i>
                                {{ __('translation.social_history.lifestyle_notes') }}
                            </label>
                            <textarea name="lifestyle_notes" class="form-control" rows="3" placeholder="{{ __('translation.social_history.lifestyle_notes_placeholder') }}">{{ $patient->lifestyle_notes }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
