@extends('layouts.popup.small')

@section('title')
    {{ trans('messages.preheader.add') }}
@endsection

@section('content')
    <form id="PreheaderForm" action="{{ action('CampaignController@preheaderAdd', $campaign->uid) }}" method="POST">
        {{ csrf_field() }}
        
        <p>{{ trans('messages.preheader.intro') }}</p>
        
        <div class="mb-4"> 
            <label class="form-label required" for="message">{{ trans('store.sms_funnel.message') }}</label>
            <textarea  id="message" class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}"
                name="message"
            >{{ $funnel->message }}</textarea>
    
            @if ($errors->has('message'))
                <div class="invalid-feedback"> {{ $errors->first('message') }} </div>
            @endif
            <div id="messagelHelp" class="form-text">   {{  trans('store.sms_funnel.remaining') }}  </div> 
        </div>

        <div class="mt-4 text-center">
            <button type="submit" class="btn btn-secondary me-1">{{ trans('messages.preheader.save') }}</button>
            <button type="button" class="btn btn-default close">{{ trans('messages.preheader.close') }}</button>
        </div>
    </form>

    <script>
        $(function() {
            $('#PreheaderForm').on('submit', function(e) {
                e.preventDefault();

                var url = $(this).attr('action');
                var data = $(this).serialize();

                addMaskLoading();

                // 
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    globalError: false,
                    statusCode: {
                        // validate error
                        400: function (res) {
                            campaignPreheader.addPopup.loadHtml(res.responseText);

                            // remove masking
                            removeMaskLoading();
                        }
                    },
                    success: function (response) {
                        removeMaskLoading();

                        // notify
                        notify(response.status, '{{ trans('messages.notify.success') }}', response.message);

                        campaignPreheader.addPopup.hide();

                        campaign_Preheader.box.load();
                    }
                });
            });
        });
    </script>
@endsection