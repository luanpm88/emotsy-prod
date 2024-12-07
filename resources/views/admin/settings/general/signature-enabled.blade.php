<div class="form-group">
    <div class="d-flex" style="width:100%">
        <div class="me-5">
            <label class="fw-semibold">
                {{ trans('messages.signature.enabled') }}
            </label>
            <p class="checkbox-description mt-1 mb-0">
                {{ trans('messages.setting.signature.enabled.help') }}
            </p>
        </div>
            
        <div class="d-flex align-items-top ms-auto">
            <label class="checker">
                <input type="hidden" name="general[signature.enabled]" value="no" />
                <input
                    type="checkbox"
                    name="general[signature.enabled]"
                    value="yes" class="styled4"
                    {{ Acelle\Model\Setting::get('signature.enabled') == 'yes' ? 'checked' : '' }}
                >
                <span class="checker-symbol"></span>
            </label>
        </div>
    </div>
</div>