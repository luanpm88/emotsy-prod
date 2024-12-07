<div class="form-group">
    <div class="d-flex" style="width:100%">
        <div class="me-5">
            <label class="fw-semibold">
                {{ trans('messages.campaign.duplicate') }}
            </label>
            <p class="checkbox-description mt-1 mb-0">
                {{ trans('messages.setting.campaign.duplicate.help') }}
            </p>
        </div>
            
        <div class="d-flex align-items-top ms-auto">
            <label class="checker">
                <input type="hidden" name="general[campaign.duplicate]" value="no" />
                <input
                    type="checkbox"
                    name="general[campaign.duplicate]"
                    value="yes" class="styled4"
                    {{ Acelle\Model\Setting::get('campaign.duplicate') == 'yes' ? 'checked' : '' }}
                >
                <span class="checker-symbol"></span>
            </label>
        </div>
    </div>
</div>