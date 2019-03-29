<div class="modal-content">
    <div class="modal-header bg-grey">
        <h5 class="modal-title">{{ trans('messages.subscription') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-20">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <h2 class="text-semibold">{{ trans('messages.subscription.logs') }}</h2>
                        
                            @if (!$gateway->isSupportRecurring())
                                <div class="sub-section">
                                        
                                    <table class="table table-box pml-table table-log mt-10">
                                        <tr>
                                            <th>{{ trans('messages.invoice.created_at') }}</th>
                                            <th>{{ trans('messages.invoice.description') }}</th>
                                            <th>{{ trans('messages.invoice.period_ends_at') }}</th>
                                            <th>{{ trans('messages.invoice.amount') }}</th>
                                            <th>{{ trans('messages.invoice.status') }}</th>
                                        </tr>
                                        @forelse ($subscription->getInvoices($gateway) as $key => $invoice)
                                            <tr>
                                                <td>
                                                    <span class="no-margin kq_search">
                                                        {{ Acelle\Library\Tool::formatDate(Carbon\Carbon::createFromTimestamp($invoice->createdAt)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="no-margin kq_search">
                                                        {!! $invoice->description !!}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="no-margin kq_search">
                                                        {{ Acelle\Library\Tool::formatDate(Carbon\Carbon::createFromTimestamp($invoice->periodEndsAt)) }}
                                                    </span>
                                                </td>                        
                                                <td>
                                                    <span class="no-margin kq_search">
                                                        {{ $invoice->amount }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="no-margin kq_search">
                                                        {{ $invoice->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="5">
                                                    {{ trans('messages.subscription.logs.empty') }}
                                                </td>
                                            </te>
                                        @endforelse
                                    </table>
                                </div>
                            @endif
                                
                            <a target="_blank"
                                href="{{ action('Admin\SubscriptionController@downloadRawInvoice', $subscription->uid) }}"
                                class="btn bg-grey-600 mr-10"
                                data-size="sm"
                            >
                                {{ trans('messages.subscription.download_raw_invoices') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>