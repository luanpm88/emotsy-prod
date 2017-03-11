<!-- Check valid license -->
@if (\Acelle\Helpers\LicenseHelper::showNotExtendedLicensePopup())
    <div class="alert ui-pnotify-container alert-primary ui-pnotify-shadow" style="min-height: 16px; overflow: hidden;">
        <h4 class="ui-pnotify-title text-nowrap">
        {!! trans('messages.is_not_extended_license', [
            'link' => action('Admin\SettingController@general'),
        ]) !!}
        </h4>
        <div style="margin-top: 10px; clear: both; text-align: right; display: none;"></div>
    </div>
@endif