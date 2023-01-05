@extends('layouts.popup.large')

@section('bar-title')
    {{ trans('messages.subscriber_import') }}
@endsection

@section('content')
    
    <!-- Dropzone -->
	<script type="text/javascript" src="{{ URL::asset('core/dropzone/dropzone.js') }}"></script>
	<link href="{{ URL::asset('core/dropzone/dropzone.css') }}" rel="stylesheet" type="text/css">

    @include('helpers._dropzone_lang')

	<div class="popup-wizard">

        @include('subscribers.import2._sidebar', ['step' => 'upload'])
        
        <div class="wizard-content">
            <p>{!! trans('messages.subscriber.import.upload.wording', [
                'link' => url('files/csv_import_example.csv')
            ]) !!}</p>   

            <link rel="stylesheet" type="text/css" href="{{ URL::asset('core/dropzone/dropzone.css') }}" />
            @php
                $formId = uniqid();
            @endphp
            <form action="/file-upload" id="form_{{ $formId }}" class="dropzone mb-4">
                {{ csrf_field() }}
                <div class="fallback">
                    <input name="file" type="file" multiple />
                </div>
            </form>

            <a href="javascript:;"
                class="btn btn-mc_primary bg-teal-800 mt-4 mapping-step wizard-link" style="display:none"
            >
                {{ trans('messages.subscriber.import.next_mapping') }}
            </a>
        </div>
    </div>

    <script>
        var SubscribersImport2 = {
            mappingUrl: null,
        }

        var myDropzone = new Dropzone("#form_{{ $formId }}", {
            url: "{{ action('SubscriberController@import2Upload', $list->uid) }}",
            maxFiles: 1,
            success: function(file, res) {
                $('.mapping-step').show();
                // disable dropzone
                myDropzone.disable();

                SubscribersImport2.mappingUrl = res.mappingUrl;

                // notify
                notify(res.status, '{{ trans('messages.notify.success') }}', res.message); 
            },
            error: function(file, res) {
                // remove error files
                myDropzone.removeAllFiles();

                // notify
                notify(res.status, '{{ trans('messages.notify.error') }}', res.message); 
            },
        });

        $('.wizard-link').click(function(e){
            e.preventDefault();

            wizard.load(SubscribersImport2.mappingUrl);
        });
    </script>
@endsection