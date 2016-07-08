@extends('layouts.frontend')

@section('page_script')    
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/visualization/echarts/echarts.js') }}"></script>
    
    <script type="text/javascript" src="{{ URL::asset('js/chart.js') }}"></script>
@endsection

@section('content')
    <h1>{{ trans('messages.dashboard') }}</h1>
    
	
    <h2 class="text-teal-800">{{ trans('messages.recently_sent_campaigns') }}</h2>
    
    <div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'select',
                'class' => 'dashboard-campaign-select',
                'name' => 'campaign_id',
                'label' => '',
                'value' => '',
                'options' => Mailixa\Model\Campaign::getSelectOptions(Auth::user()),
            ])
        </div>
    </div>
    <div class="campaign-quickview-container" data-url="{{ action("CampaignController@quickView") }}"></div>
    
    <br />
    <br />
    
    <h2 class="text-teal-800">{{ trans('messages.list_growth') }}</h2>
    
    <div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'select',
                'class' => 'dashboard-list-select',
                'name' => 'list_id',
                'label' => '',
                'value' => '',
                'include_blank' => trans('messages.all'),
                'options' => Mailixa\Model\MailList::getSelectOptions(Auth::user()),
            ])
        </div>
    </div>
    <div class="list-quickview-container" data-url="{{ action("MailListController@quickView") }}"></div>
    
    <br />
    <br />
    
    <h2 class="text-teal-800">{{ trans('messages.top_5') }}</h2>
    <div class="tabbable">
				<ul class="nav nav-tabs nav-tabs-top">
					<li class="active text-semibold"><a href="#top-tab1" data-toggle="tab">
						<i class="icon-folder-open3"></i> {{ trans('messages.campaign_opens') }}</a></li>
					<li class="text-semibold"><a href="#top-tab2" data-toggle="tab">
						<i class="icon-pointer"></i> {{ trans('messages.campaign_clicks') }}</a></li>
					<li class="text-semibold"><a href="#top-tab3" data-toggle="tab">
						<i class="icon-link"></i> {{ trans('messages.clicked_links') }}</a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane active" id="top-tab1">
						<ul class="modern-listing mt-0 top-border-none">
							@foreach (Mailixa\Model\Campaign::topOpens()->get() as $num => $item)
								<li>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <i class="number">{{ $num+1 }}</i>
                                            <h6 class="mt-0 mb-0 text-semibold">
                                                <a href="">
                                                    {{ $item->name }}
                                                </a>
                                            </h6>
                                            <p>
												@if (is_object($item->segment))
													{{ $item->mailList->name }} . {{ $item->segment->name }}													
												@elseif (is_object($item->mailList))
													{{ $item->mailList->name }}
												@endif                                                
                                            </p>
                                        </div>
										<div class="col-md-2 text-left">
											<h5 class="no-margin text-bold">
												{{ $item->aggregate }}
											</h5>
											<span class="text-muted">{{ trans('messages.opens') }}</span>
                                                <br /><br />
                                        </div>
                                        <div class="col-md-2 text-left">
											<h5 class="no-margin text-bold">
												{{ $item->openUniqCount() }}
											</h5>
											<span class="text-muted">{{ trans('messages.uniq_opens') }}</span>
                                                <br /><br />
                                        </div>
                                    </div>

                                </li>
							@endforeach
						</ul>
					</div>
					<div class="tab-pane" id="top-tab2">
						<ul class="modern-listing mt-0 top-border-none">
							@foreach (Mailixa\Model\Campaign::topClicks()->get() as $num => $item)
								<li>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <i class="number">{{ $num+1 }}</i>
                                            <h6 class="mt-0 mb-0 text-semibold">
                                                <a href="">
                                                    {{ $item->name }}
                                                </a>
                                            </h6>
                                            <p>
												@if (is_object($item->segment))
													{{ $item->mailList->name }} . {{ $item->segment->name }}													
												@elseif (is_object($item->mailList))
													{{ $item->mailList->name }}
												@endif                                                
                                            </p>
                                        </div>
                                        <div class="col-md-2 text-left">
											<h5 class="no-margin text-bold">
												{{ $item->aggregate }}
											</h5>
											<span class="text-muted">{{ trans('messages.clicks') }}</span>
                                                <br /><br />
                                        </div>
										<div class="col-md-2 text-left">
											<h5 class="no-margin text-bold">
												{{ $item->openCount() }}
											</h5>
											<span class="text-muted">{{ trans('messages.opens') }}</span>
                                                <br /><br />
                                        </div>
                                    </div>

                                </li>
							@endforeach
						</ul>
					</div>
					<div class="tab-pane" id="top-tab3">
						
						<ul class="modern-listing mt-0 top-border-none">
							@foreach (Mailixa\Model\Campaign::topLinks()->get() as $num => $item)
								<li>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <i class="number">{{ $num+1 }}</i>
                                            <h6 class="mt-0 mb-0 text-semibold">
                                                <a href="">
                                                    {{ $item->url }}
                                                </a>
                                            </h6>
                                            <p>
												{{ $item->campaigns()->count() }} {{ trans('messages.campaigns') }}                                              
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-left">
											<h5 class="no-margin text-bold">
												{{ $item->aggregate }}
											</h5>
											<span class="text-muted">{{ trans('messages.clicks') }}</span>
                                                <br /><br />
                                        </div>
                                    </div>

                                </li>
							@endforeach
						</ul>
					</div>					
				</div>
			</div>
				
	<br />
    <br />
    <h2 class="text-teal-800">{{ trans('messages.action_log') }}</h2>
	<div class="scrollbar-box action-log-box">
		<!-- Timeline -->
		<div class="timeline timeline-left content-group">
			<div class="timeline-container">				
					@foreach (Auth::user()->logs as $log)
						<!-- Sales stats -->
						<div class="timeline-row">
							<div class="timeline-icon">
								<a href="#"><img src="{{ action('UserController@avatar', $log->user->uid) }}" alt=""></a>
							</div>

							<div class="panel panel-flat timeline-content">
								<div class="panel-heading">
									<h6 class="panel-title text-semibold">{{ $log->user->displayName() }}</h6>
									<div class="heading-elements">
										<span class="heading-text"><i class="icon-history position-left text-success"></i> {{ $log->created_at->diffForHumans() }}</span>
									</div>
								</div>

								<div class="panel-body">
									{!! $log->message() !!}
								</div>
							</div>
						</div>
						<!-- /sales stats -->
					@endforeach								
			</div>
		</div>
	</div>
	
	<br>
	<br>
    
@endsection
