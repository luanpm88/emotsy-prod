<div class="form-group">
    <div class="d-flex" style="width:100%">
        <div class="me-5">
            <label class="fw-semibold">
                {{ trans('messages.email.default.skip_failed_message') }}
            </label>
            <p class="checkbox-description mt-1 mb-0">
                {{ trans('messages.setting.email.default.skip_failed_message.help') }}
            </p>
        </div>
            
        <div class="d-flex align-items-top ms-auto">
            <label class="checker">
                <input type="hidden" name="general[email.default.skip_failed_message]" value="no" />
                <input
                    type="checkbox"
                    name="general[email.default.skip_failed_message]"
                    value="yes" class="styled4"
                    {{ Acelle\Model\Setting::get('email.default.skip_failed_message') == 'yes' ? 'checked' : '' }}
                >
                <span class="checker-symbol"></span>
            </label>
        </div>
    </div>
</div>