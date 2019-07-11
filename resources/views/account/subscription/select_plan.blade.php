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
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="sub-section">
                @include('elements._notification', [
                    'level' => 'warning',
                    'message' => trans('messages.no_plan.title')
                ])
        
                <p>{{ trans('messages.select_plan.wording') }}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="sub-section">
                <div class="price-box">
                    <table class="price-table">
                        <tr>
                            <td class="bg-mc_lightblue" width="15%"><label class="section-title">{{ trans('messages.plan') }}</label></td>
                            @foreach ($plans as $plan)
                                <td width="{{ $colWidth }}%">
                                    <lable class="plan-title">{{ $plan->name }}</lable>
                                    <p>{{ $plan->description }}</p>
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="bg-mc_lightblue" width="15%"><label class="section-title">{{ trans('messages.plan.pricing') }}</label></td>
                            @foreach ($plans as $plan)
                                <td width="{{ $colWidth }}%" class="text-center">
                                    <div>{{ trans('messages.plan.starting_at') }}</div>
                                    <div class="plan-price">
                                        {{ \Acelle\Library\Tool::format_price($plan->price, $plan->currency->format) }}
                                    </div>
                                    <div>{{ $plan->displayFrequencyTime() }}</div>
                                        
                                    <a
                                        href="{{ action('AccountSubscriptionController@review', ['plan_uid' => $plan->uid]) }}"
                                        class="btn btn-mc_primary btn-mc_mk mt-30">
                                            {{ trans('messages.plan.select') }}
                                    </a>
                                        
                                    <!--<a link-method="POST"
                                        href="{{ action('AccountSubscriptionController@create', ['plan_uid' => $plan->uid]) }}"
                                        class="btn btn-mc_primary btn-mc_mk mt-30">
                                            {{ trans('messages.plan.select') }}
                                    </a>-->
                                </td>
                            @endforeach
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    

@endsection