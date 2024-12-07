<div class="form-group">
    <div class="d-flex" style="width:100%">
        <div class="me-5">
            <label class="fw-semibold">
                {{ trans('messages.embedded_form_recaptcha') }}
            </label>
            <p class="checkbox-description mt-1 mb-0">
                {{ trans('messages.setting.embedded_form_recaptcha.help') }}
            </p>
        </div>
            
        <div class="d-flex align-items-top ms-auto">
            <label class="checker">
                <input type="hidden" name="general[embedded_form_recaptcha]" value="no" />
                <input
                    type="checkbox"
                    name="general[embedded_form_recaptcha]"
                    value="yes" class="styled4"
                    {{ Acelle\Model\Setting::get('embedded_form_recaptcha') == 'yes' ? 'checked' : '' }}
                >
                <span class="checker-symbol"></span>
            </label>
        </div>
    </div>
</div>