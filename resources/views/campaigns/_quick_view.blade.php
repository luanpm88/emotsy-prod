			<h4 class="mt-10 text-semibold">{{ trans('messages.sent_to') }} <span class="text-teal text-bold">2</span> {{ trans('messages.recipients') }}</h4>
            
            <div class="row">
                <div class="col-md-6 campaigns-summary">
                    <div class="mb-10">
                        <span class="text-bold text-muted">{{ trans('messages.from') }}:</span>
                        @if (is_object($campaign->segment))
                            {{ $campaign->mailList->name }} . {{ $campaign->segment->name }}                        
                        @elseif (is_object($campaign->mailList))
                            {{ $campaign->mailList->name }}
                        @endif
                    </div>
                    <div class="mb-10">
                        <span class="text-bold text-muted">{{ trans('messages.subject') }}:</span>
                        {{ $campaign->subject }}  
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-10">
                        <span class="text-bold text-muted">{{ trans('messages.delivery_at') }}:</span>
                        {{ isset($campaign->delivery_at) ? Tool::formatDateTime(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $campaign->delivery_at)) : "" }}
                    </div>
                </div>
            </div>
			
			
            <br />
            @include("campaigns._chart")
            <br />
			@include("campaigns._open_click_rate")
            
            <br />
            
            @include("campaigns._24h_chart")
