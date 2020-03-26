@extends('layouts.frontend')

@section('title', $list->name . ": " . trans('messages.import'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')

    @include("lists._header")

@endsection

@section('content')

	@include("lists._menu")

    <div class="row">
        <div class="col-md-6">
            <h2 class="">
                {{ trans('messages.subscribers.import_csv') }}
            </h2>
            <p class="">{!! trans('messages.subscribers.import_csv.intro', [
                'csv_link' => url('files/csv_import_example.csv')
            ]) !!}</p>

            <a href="{{ action('SubscriberController@import2Wizard', $list->uid) }}" class="btn btn-mc_primary start-import">{{ trans('messages.subscriber.import.start') }}</a>
        </div>
    </div>
	
	<script>
        var wizard;

        $(function() {
            $('.start-import').click(function(e) {
                e.preventDefault();

                wizard = new Popup($(this).attr('href'));
                wizard.load();
            });
        });
	</script>
@endsection
