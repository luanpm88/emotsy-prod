<div class="modal-header">
    <h5 class="modal-title">{{ trans('messages.customer.admin_area') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <p class="alert alert-info">{!! trans('messages.current_login_as', ["name" => Auth::user()->customer->displayName()]) !!}</p>

    <h5 class="mt-0 mb-10"><i class="icon-magazine"></i> {{ trans('messages.profile_of', ['name' => $customer->displayName()]) }}</h5>
    
    @if (isset($subscription))
        <ul class="dotted-list topborder section">
            <li>
                <div class="unit size1of2">
                    <strong>{{ trans('messages.plan_name') }}</strong>
                </div>
                <div class="lastUnit size1of2">
                    <mc:flag><strong>{{ $subscription->plan->name }}</strong></mc:flag>
                </div>
            </li>
            @if ($subscription->active())
                @if ($subscription->recurring())
                    <li>
                        <div class="unit size1of2">
                            <strong>{{ trans('messages.subscription.status.recurring') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag><strong>{{ trans('messages.yes') }}</strong></mc:flag>
                        </div>
                    </li>
                @else
                    <li>
                        <div class="unit size1of2">
                            <strong>{{ trans('messages.subscription.status.recurring') }}</strong>
                        </div>
                        <div class="lastUnit size1of2">
                            <mc:flag><strong>{{ trans('messages.no') }}</strong></mc:flag>
                        </div>
                    </li>
                @endif
            @endif
            <li>
                <div class="unit size1of2">
                    <strong>{{ trans('messages.status') }}</strong>
                </div>
                <div class="lastUnit size1of2">
                    <mc:flag>
                        @if (!$subscription->active())
							@if ($subscription->ended())
								<span class="label label-flat bg-cancelled">{{ trans('messages.subscription.status.ended') }}</span>
							@elseif ($subscription->isNew())
								<span class="label label-flat bg-new">{{ trans('messages.subscription.status.new') }}</span>
							@elseif ($subscription->isPending())
								<span class="label label-flat bg-pending">{{ trans('messages.subscription.status.pending') }}</span>
							@endif
						@else
							@if ($subscription->checkPendingPaymentForFuture($gateway))
								<span class="label label-flat bg-warning">{{ trans('messages.invoice.status.pending') }}</span>
							@endif
							@if (!$subscription->recurring())
								<span class="label label-flat bg-active">{{ trans('messages.subscription.status.active') }}</span>
							@endif
							@if ($subscription->ended())
								<span class="label label-flat bg-ended">{{ trans('messages.subscription.status.ended') }}</span>
							@endif
							@if ($subscription->cancelled() && !$subscription->ended())
								<!--<span class="label label-flat bg-cancelled">{{ trans('messages.subscription.status.cancelled') }}</span>-->
							@endif
							@if ($subscription->recurring())
								<span class="label label-flat bg-info">{{ trans('messages.subscription.status.recurring') }}</span>
							@endif
						@endif
                    </mc:flag>
                </div>
            </li>
            <li>
                <div class="unit size1of2">
                    <strong>{{ trans('messages.payment_method') }}</strong>
                </div>
                <div class="lastUnit size1of2">
                    <mc:flag>{{ trans('messages.payment.' . Acelle\Model\Setting::get('system.payment_gateway')) }}</mc:flag>
                </div>
            </li>
            <li>
                <div class="unit size1of2">
                    <strong>{{ trans('messages.subscription.start_from') }}</strong>
                </div>
                <div class="lastUnit size1of2">
                    <mc:flag>{{ $subscription->created_at->diffForHumans() }}</mc:flag>
                </div>
            </li>
            <li>
                <div class="unit size1of2">
                    <strong>{{ trans('messages.subscription.next_billing_date') }}</strong>
                </div>
                <div class="lastUnit size1of2">
                    <mc:flag>{{ Acelle\Library\Tool::formatDate($next_billing_date) }}</mc:flag>
                </div>
            </li>
        </ul>
        
    @endif
</div>
<div class="modal-footer text-left">
    <a href="{{ action("CustomerController@loginBack") }}" class="btn btn-mc_primary">{{ trans('messages.customer.back_to_admin') }}</a>
    <button type="button" class="btn btn-mc_inline" data-dismiss="modal">{{ trans('messages.close') }}</button>
</div>