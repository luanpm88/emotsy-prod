<div class="form-group">
    <div class="d-flex" style="width:100%">
        <div class="me-5">
            <label class="fw-semibold">
                {{ trans('messages.email.enable_one_click_unsubscribe_headers') }}
            </label>
            <p class="checkbox-description mt-1 mb-0">
                {{ trans('messages.setting.email.enable_one_click_unsubscribe_headers.help') }}
            </p>
        </div>
            
        <div class="d-flex align-items-top ms-auto">
            <label class="checker">
                <input type="hidden" name="general[email.enable_one_click_unsubscribe_headers]" value="no" />
                <input
                    type="checkbox"
                    name="general[email.enable_one_click_unsubscribe_headers]"
                    value="yes" class="styled4"
                    {{ Acelle\Model\Setting::get('email.enable_one_click_unsubscribe_headers') == 'yes' ? 'checked' : '' }}
                >
                <span class="checker-symbol"></span>
            </label>
        </div>
    </div>
</div>