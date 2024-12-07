<div class="form-group">
    <div class="d-flex" style="width:100%">
        <div class="me-5">
            <label class="fw-semibold">
                {{ trans('messages.enable_user_registration') }}
            </label>
            <p class="checkbox-description mt-1 mb-0">
                {{ trans('messages.setting.enable_user_registration.help') }}
            </p>
        </div>
            
        <div class="d-flex align-items-top ms-auto">
            <label class="checker">
                <input type="hidden" name="general[enable_user_registration]" value="no" />
                <input
                    type="checkbox"
                    name="general[enable_user_registration]"
                    value="yes" class="styled4"
                    {{ Acelle\Model\Setting::get('enable_user_registration') == 'yes' ? 'checked' : '' }}
                >
                <span class="checker-symbol"></span>
            </label>
        </div>
    </div>
</div>