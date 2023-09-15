@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h2 class="mt-0">{{ trans('messages.campaign.reject') }}</h2>
            <p>{{ trans('messages.campaign.reject.leave_note') }}</p>

            <form enctype="multipart/form-data" action="{{ action('Admin\CampaignController@reject', $campaign->uid) }}" method="POST" class="form-validate-jquery reject-form">
		        {{ csrf_field() }}
                @include('helpers.form_control', [
                    'type' => 'textarea',
                    'name' => 'reason',
                    'label' => '',
                    'value' => '',
                    'rules' => ['reason' => 'required']
                ])

                <button class="btn btn-secondary">{{ trans('messages.campaign.reject') }}</button>
            </form>
        </div>
    </div>
        
    <script>
        $('.reject-form').submit(function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var data = $(this).serialize();

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                globalError: false,
                statusCode: {
                    // validate error
                    400: function (res) {
                        rejectPendingSub.loadHtml(res.responseText);
                    }
                },
                success: function (response) {
                    rejectPendingSub.hide();

                    // notify
                    notify({
                        type: 'success',
                        title: '{!! trans('messages.notify.success') !!}',
                        message: response.message
                    });

                    // reload if not in list page
                    if (typeof CampaignsIndex == 'undefined') {
                        window.location = '{{ action('Admin\CampaignController@index') }}';
                    } else {
                        CampaignsIndex.getList().load();
                    }
                }
            });
        });
    </script>
@endsection