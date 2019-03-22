<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <h2 class="text-semibold">{{ trans('messages.subscription.logs') }}</h2>
    
        <div class="sub-section">
                
            <table class="table table-box pml-table table-log mt-10">
                <tr>
                    <th>{{ trans('messages.invoice.datetime') }}</th>
                    <th>{{ trans('messages.invoice.description') }}</th>
                    <th>{{ trans('messages.invoice.amount') }}</th>
                    <th>{{ trans('messages.invoice.status') }}</th>
                </tr>
                @forelse ($subscription->getInvoices($gateway) as $key => $invoice)
                    <tr>
                        <td>
                            <span class="no-margin kq_search">
                                {{ Acelle\Library\Tool::formatDateTime(Carbon\Carbon::createFromTimestamp($invoice->time)) }}
                            </span>
                        </td>
                        <td>
                            <span class="no-margin kq_search">
                                {{ $invoice->description }}
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
                        <td class="text-center" colspan="4">
                            {{ trans('messages.subscription.logs.empty') }}
                        </td>
                    </te>
                @endforelse
            </table>
        </div>
    </div>
</div>