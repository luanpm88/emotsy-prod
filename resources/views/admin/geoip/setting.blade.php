@extends('layouts.popup.small')

@section('title')
    GeoIp Setting
@endsection

@section('content')
    <form id="GeoIpSetting" class="billing-address-form form-validate-jquery" action="{{ action('Admin\GeoIpController@setting') }}"
        method="POST">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-md-10">
                @include('helpers.form_control', [
                    'type' => 'text',
                    'name' => 'source',
                    'label' => 'Enter database source',
                    'value' => '',
                    'required' => true,
                    'rules' => ['source' => 'required'],
                ])
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-mc_primary">{{ trans('messages.save') }}</button>
            <button type="button" class="btn btn-link" onclick="GoeIpIndex.settingPopup.hide();">{{ trans('messages.cancel') }}</button>
        </div>
    </form>

    <script>
        var GeoIpSetting = {
            init: function() {
                GeoIpSetting.form.on('submit', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('action');

                    GeoIpSetting.submit(url, GeoIpSetting.form.serialize());
                })
            },

            form: $('#GeoIpSetting'),

            submit: function(url, data) {
                addMaskLoading('Updating GeoIp setting...');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    globalError: false,
                    statusCode: {
                        400: function (response) {
                            // validation
                            GoeIpIndex.settingPopup.loadHtml(response.responseText);
                        }
                    },
                    success: function (response) {
                        GoeIpIndex.settingPopup.hide();
                
                        // notify
                        notify(response.status, '{{ trans('messages.notify.success') }}', response.message); 
                    }
                }).always(function() {
                    removeMaskLoading();
                });
            }
        }

        GeoIpSetting.init();
    </script>
@endsection