@extends('layouts.core.frontend', [
	'menu' => 'subscriber',
])

@section('title', $list->name . ": " . trans('messages.import'))

@section('page_header')

    @include("lists._header")

@endsection

@section('content')

	@include("lists._menu", [
		'menu' => 'subscriber_import',
	])

    @if ($currentJob)
        <div class="row">
            <div class="col-md-6">
                <h2 class="my-4">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border text-success me-3 fw-normal fs-6" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span>{{ trans('messages.import.in_progress') }}</span>
                    </div>
                </h2>
                <p class="">{!! trans('messages.import.in_progress.wording') !!}</p>

                <a id="ViewImportProgressButton" href="javascript:;" class="btn btn-mc_primary start-import">
                    {{ trans('messages.import.in_progress.view') }}
                </a>
            </div>
        </div>

        <script>
            var wizard;

            var SubscribersImport = {
                progessUrl: '{{ action('SubscriberController@import2Progress', [
                    'list_uid' => $list->uid,
                    'job_uid' => $currentJob->uid,
                ]) }}',

                openProgressPopup: function() {
                    wizard = new Popup({
                        onclose: function() {
                            window.location.reload();
                        }
                    });
                    wizard.load(this.progessUrl);
                }
            };

            $(function() {
                $('#ViewImportProgressButton').on('click', function() {
                    SubscribersImport.openProgressPopup();
                });
            });
        </script>

    @else
        <div class="row">
            <div class="col-md-6">
                <h2 class="my-4">
                    {{ trans('messages.subscribers.import_csv') }}
                </h2>
                <p class="">{!! trans('messages.subscribers.import_csv.intro', [
                    'csv_link' => url('files/csv_import_example.csv')
                ]) !!}</p>

                <a mapping-action="upload" href="javascript:;" class="btn btn-mc_primary">
                    {{ trans('messages.subscriber.import.start') }}
                </a>
            </div>
        </div>
        
        <script>
            var SubscribersImport2 = {
                popup: null,
                uploadUrl: '{{ action('SubscriberController@import2Wizard', $list->uid) }}',
                
                init: function() {
                    this.popup = new Popup();
                },

                getPopup: function() {
                    return this.popup;
                },

                loadUploadPopup: function() {
                    this.getPopup().load(this.uploadUrl);
                }
            }

            $(function() {
                // init
                SubscribersImport2.init();

                // load upload popup button
                $('[mapping-action="upload"]').on('click', function() {
                    SubscribersImport2.loadUploadPopup();
                });
            });
        </script>
    @endif
@endsection
