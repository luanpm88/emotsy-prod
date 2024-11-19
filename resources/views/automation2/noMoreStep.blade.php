@extends('layouts.popup.small')

@section('content')
    <div class="row">
        <div class="col-md-12">

            <p class="alert alert-danger">
                {{ trans('messages.automation.no_more_step', [
                    'limit' => \Auth::user()->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('automation_step')
                ]) }}
            </p>
                
        </div>
    </div>
@endsection