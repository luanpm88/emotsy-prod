@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <h3 class="mb-3">{{ trans('messages.automation.add_an_action') }}</h3>
            <p>{{ trans('messages.automation.action.intro') }}</p>
                
            <div class="line-list">
                @foreach ($types as $type)
                    <div class="line-item action-select-but" data-key="{{ $type }}">
                        <div class="line-icon">
                            <i class="lnr lnr-{{ trans('messages.automation.action.' . $type . '.icon') }}"></i>
                        </div>
                        <div class="line-body">
                            <h5>{{ trans('messages.automation.action.' . $type) }}</h5>
                            <p>{{ trans('messages.automation.action.' . $type . '.desc') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
