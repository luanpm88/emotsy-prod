<div class="form-group">
    <div class="d-flex" style="width:100%">
        <div class="me-5">
            <label class="fw-semibold">
                {{ trans('messages.list.default.double_optin') }}
            </label>
            <p class="checkbox-description mt-1 mb-0">
                {{ trans('messages.setting.list.default.double_optin.help') }}
            </p>
        </div>
            
        <div class="d-flex align-items-top ms-auto">
            <label class="checker">
                <input type="hidden" name="general[list.default.double_optin]" value="no" />
                <input
                    type="checkbox"
                    name="general[list.default.double_optin]"
                    value="yes" class="styled4"
                    {{ Acelle\Model\Setting::get('list.default.double_optin') == 'yes' ? 'checked' : '' }}
                >
                <span class="checker-symbol"></span>
            </label>
        </div>
    </div>
</div>