<div class="form-group">
    <div class="d-flex" style="width:100%">
        <div class="me-5">
            <label class="fw-semibold">
                {{ trans('messages.tracking_domain.enable_https') }}
            </label>
            <p class="checkbox-description mt-1 mb-0">
                {{ trans('messages.setting.tracking_domain.enable_https.help') }}
            </p>
        </div>
            
        <div class="d-flex align-items-top ms-auto">
            <label class="checker">
                <input type="hidden" name="general[tracking_domain.enable_https]" value="no" />
                <input
                    type="checkbox"
                    name="general[tracking_domain.enable_https]"
                    value="yes" class="styled4"
                    {{ Acelle\Model\Setting::get('tracking_domain.enable_https') == 'yes' ? 'checked' : '' }}
                >
                <span class="checker-symbol"></span>
            </label>
        </div>
    </div>
</div>