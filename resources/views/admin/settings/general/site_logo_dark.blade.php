<div class="form-group {{ $errors->has('site_logo_dark') ? 'has-error' : '' }} control-image">
    <label>
        {{ trans('messages.site_logo_dark') }}
    </label>
    <div class="row">
        <div class="col-md-9">
            <input value="" type="file" name="site_logo_dark" class="form-control file-styled-primary">
        </div>
        <div class="col-md-3">
            <div class="p-3 box-shadow-sm rounded" style="background-color: #f6f6f6;">
                @if (isset($setting['value']))
                    <img width="100%" src="{{ action('SettingController@file', $setting['value']) }}" />
                @endif
            </div>
        </div>
    </div>
</div>