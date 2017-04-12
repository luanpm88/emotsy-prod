<!-- Check valid license -->
<!--if (!\Acelle\Model\Setting::get('license') && !\Request::is('*license*'))-->
@if (!\Acelle\Model\Setting::get('license') && !\Request::is('*license*'))
    <div class="alert ui-pnotify-container alert-primary ui-pnotify-shadow" style="min-height: 16px; overflow: hidden;">
        <h4 class="ui-pnotify-title text-nowrap">
        {!! trans('messages.' . \Acelle\Library\Tool::currentView() . '_not_have_license', [
            'link' => action('Admin\SettingController@license'),
        ]) !!}
        </h4>
        <div style="margin-top: 10px; clear: both; text-align: right; display: none;"></div>
    </div>
@elseif (Acelle\Helpers\LicenseHelper::showNotExtendedLicensePopup())
    <div class="alert ui-pnotify-container alert-primary ui-pnotify-shadow" style="min-height: 16px; overflow: hidden;">
        <h4 class="ui-pnotify-title text-nowrap">
            {!! trans('messages.is_not_extended_license', ['link' => action('Admin\SettingController@license')]) !!}
        </h4>
        <div style="margin-top: 10px; clear: both; text-align: right; display: none;"></div>
    </div>
@endif
