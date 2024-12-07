<div class="form-group">
    <div class="d-flex" style="width:100%">
        <div class="me-5">
            <label class="fw-semibold">
                {{ trans('messages.list_sign_up_captcha') }}
            </label>
            <p class="checkbox-description mt-1 mb-0">
                {{ trans('messages.setting.list_sign_up_captcha.help') }}
            </p>
        </div>
            
        <div class="d-flex align-items-top ms-auto">
            <label class="checker">
                <input type="hidden" name="general[list_sign_up_captcha]" value="no" />
                <input
                    type="checkbox"
                    name="general[list_sign_up_captcha]"
                    value="yes" class="styled4"
                    {{ Acelle\Model\Setting::get('list_sign_up_captcha') == 'yes' ? 'checked' : '' }}
                >
                <span class="checker-symbol"></span>
            </label>
        </div>
    </div>
</div>