@extends('layouts.frontend')

@section('title', trans('messages.subscriptions'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="active">{{ trans('messages.subscription') }}</li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-profile"></i> {{ Auth::user()->customer->displayName() }}</span>
        </h1>
    </div>

@endsection

@section('content')

    @include("account._menu")

    <div class="row">
        <div class="col-md-12">
            <h2 class="text-semibold">{{ trans('messages.subscription') }}</h2>

            <div class="sub-section">
				<p>
                    {!! trans('messages.subscription.top_up.' . $gateway['name'], [
                        'plan' => $subscription->plan->name,
                        'price' => Acelle\Library\Tool::format_price($subscription->plan->price, $subscription->plan->currency->format)
                    ]) !!}
                </p>
                
                @include("account.subscription.top_up._" . $gateway['name'])
                
			</div>
        </div>
    </div>

@endsection
