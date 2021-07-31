@if ($bill)
    <div class="card">
        <div class="card-header py-3">
            <div class="d-flex align-items-center">
                <div>
                    <h5 class="fw-600 mb-0 fs-6 text-start">
                        {{ $bill['title'] }}
                    </h5>
                    <p class="m-0 text-muted">{!! $bill['description'] !!}</p>
                </div>
            </div>
                
        </div>
        <div class="card-body">
            <div>
                @foreach ($bill['bill'] as $item)
                    <div class="bill_item d-flex">
                        <div class="mr-auto">
                            <div class="font-weight-semibold">{{ trans('messages.invoice.price') }}</div>
                            <p class="mb-0">({{ $item['title'] }})</p>
                            <div class="mb-0">{!! $item['description'] !!}</div>
                        </div>
                        <div class="font-weight-semibold">{{ $item['price'] }}</div>
                    </div>
                    <div class="bill_item d-flex">
                        <div class="mr-auto">
                            <div class="font-weight-semibold">{{ trans('messages.bill.tax') }}</div>
                        </div>
                        <div class="font-weight-semibold">{{ $item['tax'] }}</div>
                    </div>
                @endforeach
            </div>
            <hr>
            <div>
                <div class="total d-flex ">
                    <div class="mr-auto">
                        <div class="font-weight-semibold">{{ trans('messages.bill.estimated_total') }}</div>
                        <div>{!! $bill['charge_info'] !!}</div>
                    </div>
                    <div class="bill-total">{{ $bill['total'] }}</div>
                </div>
            </div>
            
            @if ($bill['pending'])
                <hr>
                <div class="text-right">
                    <a href="{{ action('SubscriptionController@payment', [
                        'invoice_uid' => $bill['invoice_uid'],
                    ]) }}" class="btn btn-warning button-loading full-width pr-20" style="pointer-events: auto;opacity:0.9">
                        {{ trans('messages.invoice.payment_is_being_verified') }}
                        <div class="loader"></div>
                    </a>
                </div>
            @elseif (isset($payButton) && !\Auth::user()->customer->preferredPaymentGatewayCanAutoCharge())
                <hr>
                <div class="text-left">
                    <a href="{{ action('SubscriptionController@payment', [
                        'invoice_uid' => $bill['invoice_uid'],
                    ]) }}" class="btn btn-secondary">
                        {{ trans('messages.invoice.pay_now') }}
                    </a>

                    @if ($bill['type'] == Acelle\Model\Invoice::TYPE_CHANGE_PLAN)
                        <a class="btn btn-link" link-method="POST" link-confirm="{{ trans('messages.invoice.cancel.confirm') }}"
                            href="{{ action('AccountSubscriptionController@cancelInvoice', [
                                'invoice_uid' => $bill['invoice_uid'],
                            ]) }}">
                            {{ trans('messages.invoice.cancel') }}
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endif