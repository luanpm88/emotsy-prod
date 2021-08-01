@if ($invoice->isNew() && !$invoice->getPendingTransaction())
    <div class="card mt-2">
        <div class="card-body">
            <div class="alert alert-info">{!! trans('messages.payment.agree_service_intro') !!}</div>
            <form id="checkoutForm" class="" action="{{ $invoice->isFree() ? action('SubscriptionController@confirmFree', [
                'invoice_uid' => $invoice->uid
            ]) : action('SubscriptionController@checkout') }}"
                method="POST">
                {{ csrf_field() }}

                <input type="hidden" name="payment_method" />

                <input type="submit" name="new_payment" {{ isset($disabled) && $disabled ? 'disabled' : '' }}
                    class="btn btn-primary py-2 fs-6 px-4 text-center rounded-3 shadow" style="width:100%"
                    value="{{ $invoice->isFree() ? trans('messages.subscription.get_started') : trans('messages.subscription.checkout') }}"
                >
            </form>
        </div>
    </div>

    <script>
        $(function() {
            $('#checkoutForm').submit(function(e) {
                var payment_method = $(this).find('[name=payment_method]').val();

                if (!payment_method) {
                    e.preventDefault();

                    new Dialog('alert', {
                        message: '{{ trans('messages.subscription.no_payment_method_selected') }}',
                    })

                    return false;
                }
            });
        });
    </script>
@endif