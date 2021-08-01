@extends('layouts.core.dark')

@section('title', trans('messages.subscriptions'))

@section('menu_title')
    @include('subscription._title')
@endsection

@section('menu_right')
    @include('layouts.core._top_activity_log')
    @include('layouts.core._menu_frontend_user')
@endsection

@section('content')
    <div class="container mt-4 mb-5">
        <div class="row">
            <div class="col-md-8">
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
                            @if (request()->user()->customer->contact)
                                @include('helpers.form_control', [
                                    'type' => 'checkbox2',
                                    'name' => 'same_as_contact',
                                    'value' => (request()->same_as_contact == 'true' ? 'true' : 'false'),
                                    'label' => trans('messages.same_as_contact'),
                                    'options' => ['false', 'true'],
                                    'help_class' => 'billing_address',
                                    'rules' => ['first_name' => 'required'],
                                ])
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    @include('helpers.form_control', [
                                        'type' => 'text',
                                        'name' => 'first_name',
                                        'value' => $billingAddress->first_name,
                                        'label' => trans('messages.first_name'),
                                        'help_class' => 'billing_address',
                                        'rules' => ['first_name' => 'required'],
                                        // 'readonly' => request()->same_as_contact == 'true',
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('helpers.form_control', [
                                        'type' => 'text',
                                        'name' => 'last_name',
                                        'value' => $billingAddress->last_name,
                                        'label' => trans('messages.last_name'),
                                        'help_class' => 'billing_address',
                                        'rules' => ['last_name' => 'required'],
                                        // 'readonly' => request()->same_as_contact == 'true',
                                    ])
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    @include('helpers.form_control', [
                                        'type' => 'text',
                                        'name' => 'email',
                                        'value' => $billingAddress->email,
                                        'label' => trans('messages.email_address'),
                                        'help_class' => 'billing_address',
                                        'rules' => ['email' => 'required'],
                                        // 'readonly' => request()->same_as_contact == 'true',
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('helpers.form_control', [
                                        'type' => 'text',
                                        'name' => 'phone',
                                        'value' => $billingAddress->phone,
                                        'label' => trans('messages.phone'),
                                        'help_class' => 'billing_address',
                                        // 'readonly' => request()->same_as_contact == 'true',
                                    ])
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    @include('helpers.form_control', [
                                        'type' => 'text',
                                        'name' => 'address',
                                        'value' => $billingAddress->address,
                                        'label' => trans('messages.address'),
                                        'help_class' => 'billing_address',
                                        'rules' => ['address' => 'required'],
                                        // 'readonly' => request()->same_as_contact == 'true',
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('helpers.form_control', [
                                        'type' => 'select',
                                        'name' => 'country_id',
                                        'value' => $billingAddress->country_id,
                                        'label' => trans('messages.country'),
                                        'options' => Acelle\Model\Country::getSelectOptions(),
                                        'include_blank' => trans('messages.select_country'),
                                        'help_class' => 'billing_address',
                                        'rules' => ['country_id' => 'required'],
                                        // 'readonly' => request()->same_as_contact == 'true',
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
                @include('invoices.bill', [
                    'bill' => $invoice->getBillingInfo(),
                ])

                @include('subscription._checkout', [
                    'disabled' => true,
                ])
            </div>
        </div>
    </div>

    <script>
        $(function() {
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