@if ($subscription->getPayments()->count() > 0)
    <table class="table table-box pml-table table-log">
        <tr>
            <th>{{ trans('messages.created_at') }}</th>
            <th>{{ trans('messages.payment_method') }}</th>
            <th>{{ trans('messages.order_id') }}</th>
            <th>{{ trans('messages.status') }}</th>
        </tr>
        @foreach ($subscription->getPayments()->get() as $key => $payment)
            <tr>
                <td>
                    <span class="no-margin kq_search">{{ \Acelle\Library\Tool::formatDateTime($payment->created_at) }}</span>
                    <span class="text-muted second-line-mobile">{{ trans('messages.created_at') }}</span>
                </td>
                <td>{{ $payment->getPaymentMethodName() }}</td>
                <td>{{ $payment->getOrderID() }}</td>
                <td>                
					<span class="text-muted2 list-status pull-left">
						<span
                            @if ($payment->status == 'failed')
                                data-popup='tooltip' title="{!! implode('; ', $payment->getErrorMessages()) !!}"
                            @endif
                        class="label label-sub label-flat bg-{{ $payment->status }}">{{ trans('messages.subscription_payment_status_' . $payment->status) }}</span>
					</span>
				</td>
            </tr>
        @endforeach
    </table>
@endif