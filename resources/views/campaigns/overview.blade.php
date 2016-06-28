@extends('layouts.frontend')

@section('title', $campaign->name)
	
@section('page_script')    

@endsection

@section('page_header')
	
			@include("campaigns._header")

@endsection

@section('content')
                
            @include("campaigns._menu")

            <h3 class="mt-10"><span class="text-teal text-semibold">2</span> {{ trans('messages.recipients') }}</h3>
            
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
                    <div class="mb-10">
                        <span class="text-bold text-muted">{{ trans('messages.from_email') }}:</span>
                        {{ $campaign->from_email }}  
                    </div>
                    <div class="mb-10">
                        <span class="text-bold text-muted">{{ trans('messages.from_name') }}:</span>
                        {{ $campaign->from_name }}  
                    </div>
                    <div class="mb-10">
                        <span class="text-bold text-muted">{{ trans('messages.reply_to') }}:</span>
                        {{ $campaign->reply_to }}  
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-10">
                        <span class="text-bold text-muted">{{ trans('messages.updated_at') }}:</span>
                        {{ Tool::formatDateTime($campaign->updated_at) }}
                    </div>
                    <div class="mb-10">
                        <span class="text-bold text-muted">{{ trans('messages.delivery_at') }}:</span>
                        {{ Tool::formatDateTime(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $campaign->delivery_at)) }}
                    </div>
                </div>
            </div>
                
            <hr />
            
            <div class="row">
                <div class="col-md-6">
                    <div class="content-group-sm">
                        <div class="pull-right progress-right-info text-teal-800">15%</div>
                        <h4 class="text-semibold">{{ trans('messages.open_rate') }}</h4>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-teal" style="width: 15%">
                                <span class="sr-only">15% Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-table">
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.successful_deliveries') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.total_opens') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.last_opened') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.forwarded') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="content-group-sm">
                        <div class="pull-right progress-right-info text-teal-800">15%</div>
                        <h4 class="text-semibold">{{ trans('messages.click_rate') }}</h4>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-teal" style="width: 15%">
                                <span class="sr-only">15% Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-table">
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.click_per_unique_opens') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.total_clicks') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.last_clicked') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.abuse_reports') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <br />
			
			<h3 class="text-semibold"><i class="icon-link"></i> {{ trans('messages.top_links_clicked') }}</h3>
            
			<div class="stat-table">
				<div class="stat-row">
					<div class="pull-right num">
						0%
					</div>
					<p class="text-muted">{{ trans('messages.click_per_unique_opens') }}</p>
				</div>
				<div class="stat-row">
					<div class="pull-right num">
						0%
					</div>
					<p class="text-muted">{{ trans('messages.total_clicks') }}</p>
				</div>
				<div class="stat-row">
					<div class="pull-right num">
						0%
					</div>
					<p class="text-muted">{{ trans('messages.last_clicked') }}</p>
				</div>
				<div class="stat-row">
					<div class="pull-right num">
						0%
					</div>
					<p class="text-muted">{{ trans('messages.abuse_reports') }}</p>
				</div>
			</div>
				
			<br />
			

			<div class="row">
                <div class="col-md-6">
                    <div class="content-group-sm">
                        <h4 class="text-semibold"><i class="icon-users4"></i> {{ trans('messages.subscribers_most_open') }}</h4>                        
                    </div>
                    <div class="stat-table">
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.successful_deliveries') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.total_opens') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.last_opened') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.forwarded') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="content-group-sm">
                        <h4 class="text-semibold"><i class="icon-location4"></i> {{ trans('messages.top_location_by_opens') }}</h4>
                    </div>
                    <div class="stat-table">
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.click_per_unique_opens') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.total_clicks') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.last_clicked') }}</p>
                        </div>
                        <div class="stat-row">
                            <div class="pull-right num">
                                0%
                            </div>
                            <p class="text-muted">{{ trans('messages.abuse_reports') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <br />
@endsection
