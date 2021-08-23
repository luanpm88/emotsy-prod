@extends('layouts.core.frontend_dark')

@section('title', trans('messages.subscriptions'))

@section('menu_title')
    @include('subscription._title')
@endsection

@section('menu_right')
    @include('layouts.core._top_activity_log')
    @include('layouts.core._menu_frontend_user')
@endsection

@section('content')
    <div class="container mt-4 pt-3 mb-5">
        <div class="row">
            <div class="col-md-8">
                <!-- display flash message -->
                @include('layouts.core._errors')

                @include('subscription._selectPlan')

                <div class="card mt-2 subscription-step">
                    <a href="" class="card-header py-3 select-plan-tab">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><label class="subscription-step-number">2</label></div>
                            <div>
                                <h5 class="fw-600 mb-0 fs-6 text-start">
                                    {{ trans('messages.subscription.billing_information.title') }}
                                </h5>
                                <p class="m-0 text-muted">{{ trans('messages.subscription.billing_information.subtitle') }}</p>
                            </div>
                            <div class="ms-auto">
                                <span class="material-icons-outlined fs-4 text-success">
                                    task_alt
                                    </span>
                            </div>
                        </div>
                    </a>
                    <div class="card-body py-4" style="padding-left: 72px;padding-right:72px">
                        <form class="billing-address-form" action="{{ action('SubscriptionController@billingInformation', [
                            'invoice_uid' => $invoice->uid,
                        ]) }}"
                            method="POST">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-6">
                                    @include('helpers.form_control', [
                                        'type' => 'text',
                                        'name' => 'billing_first_name',
                                        'value' => $invoice->billing_first_name,
                                        'label' => trans('messages.first_name'),
                                        'rules' => ['billing_first_name' => 'required'],
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('helpers.form_control', [
                                        'type' => 'text',
                                        'name' => 'billing_last_name',
                                        'value' => $invoice->billing_last_name,
                                        'label' => trans('messages.last_name'),
                                        'rules' => ['billing_last_name' => 'required'],
                                    ])
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    @include('helpers.form_control', [
                                        'type' => 'text',
                                        'name' => 'billing_email',
                                        'value' => $invoice->billing_email,
                                        'label' => trans('messages.email_address'),
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('helpers.form_control', [
                                        'type' => 'text',
                                        'name' => 'billing_phone',
                                        'value' => $invoice->billing_phone,
                                        'label' => trans('messages.phone'),
                                    ])
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    @include('helpers.form_control', [
                                        'type' => 'text',
                                        'name' => 'billing_address',
                                        'value' => $invoice->billing_address,
                                        'label' => trans('messages.address'),
                                        'rules' => ['billing_address' => 'required'],
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('helpers.form_control', [
                                        'type' => 'select',
                                        'name' => 'billing_country_id',
                                        'value' => $invoice->billing_country_id,
                                        'label' => trans('messages.country'),
                                        'options' => Acelle\Model\Country::getSelectOptions(),
                                        'include_blank' => trans('messages.select_country'),
                                        'rules' => ['billing_country_id' => 'required'],
                                    ])
                                </div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-secondary">{{ trans('messages.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

                @include('subscription._paymentMethod')
            </div>
            <div class="col-md-4">
                <div class="order-box" style="position: sticky;top: 80px;">

                </div>
            </div>
        </div>
    </div>

    <script>
        var SubscriptionBillingInfo = {
            orderBox: null,

            getOrderBox: function() {
                if (this.orderBox == null) {
                    this.orderBox = new Box($('.order-box'), '{{ action('SubscriptionController@orderBox') }}');
                }
                return this.orderBox;
            }
        }

        $(function() {
            SubscriptionBillingInfo.getOrderBox().load();

            $('[name=same_as_contact]').change(function() {
                var checked = $(this).is(':checked');
                
                $.ajax({
                    url: '{{ action('AccountController@editBillingAddress') }}',
                    method: 'GET',
                    data: {
                        same_as_contact: checked
                    },
                    success: function (response) {
                        billingPopup.loadHtml(response);
                    }
                });
            });
        });
    </script>
@endsection