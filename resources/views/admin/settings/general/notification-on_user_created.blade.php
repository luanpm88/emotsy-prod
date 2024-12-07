<div class="form-group">
    <div class="d-flex" style="width:100%">
        <div class="me-5">
            <label class="fw-semibold">
                {{ trans('messages.notification.on_user_created') }}
            </label>
            <p class="checkbox-description mt-1 mb-0">
                {{ trans('messages.setting.notification.on_user_created.help') }}
            </p>
        </div>
            
        <div class="d-flex align-items-top ms-auto">
            <label class="checker">
                <input type="hidden" name="general[notification.on_user_created]" value="no" />
                <input
                    type="checkbox"
                    name="general[notification.on_user_created]"
                    value="yes" class="styled4"
                    {{ Acelle\Model\Setting::get('notification.on_user_created') == 'yes' ? 'checked' : '' }}
                >
                <span class="checker-symbol"></span>
            </label>
        </div>
    </div>
</div>