<div class="sub-section">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <h2 class="text-semibold mb-10">{{ trans('messages.subscription.logs') }}</h2>
            <p>{{ trans('messages.subscription.logs.intro') }}</p>
                
            
            <table class="table table-box pml-table table-log mt-10">
                <tr>
                    <th>{{ trans('messages.invoice.created_at') }}</th>
                    <th>{{ trans('messages.invoice.description') }}</th>
                    <th>{{ trans('messages.invoice.period_ends_at') }}</th>
                    <th>{{ trans('messages.invoice.amount') }}</th>
                    <th>{{ trans('messages.invoice.status') }}</th>
                </tr>
                @forelse ($gateway->getInvoices($subscription) as $key => $invoice)
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
                
            <a target="_blank"
                href="{{ action('AccountSubscriptionController@downloadRawInvoice') }}"
                class="btn bg-grey-600 mr-10 mt-20"
                data-size="sm"
            >
                {{ trans('messages.subscription.download_raw_invoices') }}
            </a>
        </div>
    </div>
</div>