@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <h2>{{ trans('messages.automation.add_an_action') }}</h2>
            <p>{{ trans('messages.automation.action.intro') }}</p>
                
            <div class="line-list">
                @foreach ($types as $type)
                    <div class="line-item action-select-but" data-type="{{ $type }}">
                        <div class="line-icon">
                            <i class="lnr lnr-{{ trans('messages.automation.action.' . $type . '.icon') }}"></i>
                        </div>
                        <div class="line-body">
                            <h4 class="text-teal-800">{{ trans('messages.automation.action.' . $type) }}</h4>
                            <p>{{ trans('messages.automation.action.' . $type . '.desc') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
