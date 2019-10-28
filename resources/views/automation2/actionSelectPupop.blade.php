@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <h2>{{ trans('messages.automation.add_an_action') }}</h2>
            <p>{{ trans('messages.automation.action.intro') }}</p>
                
            <div class="line-list">
                <div class="line-item">
                    <div class="line-icon">
                        <i class="lnr lnr-envelope"></i>
                    </div>
                    <div class="line-body">
                        <h4 class="text-teal-800">Send an Email</h4>
                        <p>Design or send automated or regular emails to your contacts.</p>
                    </div>
                </div>
                <div class="line-item">
                    <div class="line-icon">
                        <i class="lnr lnr-alarm"></i>
                    </div>
                    <div class="line-body">
                        <h4 class="text-teal-800">Wait</h4>
                        <p>Design or send automated or regular emails to your contacts.</p>
                    </div>
                </div>
                <div class="line-item">
                    <div class="line-icon">
                        <i class="lnr lnr-code"></i>
                    </div>
                    <div class="line-body">
                        <h4 class="text-teal-800">Condition</h4>
                        <p>Design or send automated or regular emails to your contacts.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
