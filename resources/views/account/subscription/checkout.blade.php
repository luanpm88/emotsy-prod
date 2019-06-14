@extends('layouts.frontend')

@section('title', trans('messages.subscriptions'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            
            @include("cashier::" . $gatewayService->name . ".checkout")
            
        </div>
    </div>
    

@endsection