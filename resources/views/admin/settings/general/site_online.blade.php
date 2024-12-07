<div class="form-group">
    <div class="d-flex" style="width:100%">
        <div class="me-5">
            <label class="fw-semibold">
                {{ trans('messages.site_online') }}
            </label>
            <p class="checkbox-description mt-1 mb-0">
                {{ trans('messages.setting.site_online.help') }}
            </p>
        </div>
            
        <div class="d-flex align-items-top ms-auto">
            <label class="checker">
                <input type="hidden" name="general[site_online]" value="false" />
                <input
                    type="checkbox"
                    name="general[site_online]"
                    value="true" class="styled4"
                    {{ Acelle\Model\Setting::get('site_online') == 'true' ? 'checked' : '' }}
                >
                <span class="checker-symbol"></span>
            </label>
        </div>
    </div>
</div>