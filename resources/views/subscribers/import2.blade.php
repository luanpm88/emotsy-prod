@extends('layouts.core.frontend')

@section('title', $list->name . ": " . trans('messages.import'))

@section('page_header')

    @include("lists._header")

@endsection

@section('content')

	@include("lists._menu")

    <div class="row">
        <div class="col-md-6">
            <h2 class="my-4">
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
                var url = $(this).attr('href');

                wizard = new Popup({
                    url: url
                });
                wizard.load();
            });
        });
	</script>
@endsection
