@extends('layouts.install')

@section('title', trans('messages.permission'))

@section('page_script')    
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('content')

<h3 class="text-teal-800">{{ trans('messages.file_permissions') }}</h3>
	
	@if (!$result)
		<div class="alert alert-danger">
			Failed to check files/folders permissions. Please fix and try again!
		</div>
	@endif

    <div class="row">
        <div class="col-md-12">
            <ul class="modern-listing mt-10">
                @foreach ($permissions as $key => $item)
                    <li>
                        @if ($item["check"])
                            <i class="icon-checkmark4 text-success"></i>
                        @else
                            <i class="icon-cancel-circle2 text-danger"></i>
                        @endif
                        <h5 class="mt-0 mb-0 text-semibold">
                            {{ $item["name"] }}
                        </h5>
                        <p>
                            {{ $item["note"] }}
                        </p>
                    </li>
                @endforeach
            </ul>

            <div class="text-right">                                    
                @if ($result)
					<a href="{{ action('InstallController@siteInfo') }}" class="btn btn-primary bg-teal">{!! trans('messages.next') !!} <i class="icon-arrow-right14 position-right"></i></a>
				@else
					<a href="{{ action('InstallController@permissions') }}" class="btn btn-primary bg-grey-600"><i class="icon-reload-alt position-right"></i> {!! trans('messages.try_again') !!}</a>
				@endif
            </div>
        </div>
    </div>

@endsection
