@extends('layouts.frontend')

@section('title', trans('messages.campaigns') . " - " . trans('messages.template'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>        
    <script type="text/javascript" src="{{ URL::asset('tinymce/tinymce.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
        
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>	
@endsection

@section('page_header')
	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-paperplane"></i> {{ $campaign->name }}</span>
				</h1>

				@include('campaigns._steps', ['current' => 3])
			</div>

@endsection

@section('content')
			@if ($campaign->type != 'plain-text')
				<div class="pull-right">
					<a href="#" class="btn btn-info bg-teal-800" data-toggle="modal" data-target="#choose_template">{{ trans('messages.choose_template') }}</a>
				</div>
			@endif
				
			<!-- Choose template modal -->
			<div id="choose_template" class="modal fade">
				<div class="modal-dialog modal-full">
					<div class="modal-content">
							<div class="modal-header bg-info-800">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h2 class="modal-title">{{ trans('messages.choose_template') }}</h2>
							</div>
			
							<div class="modal-body">
								
								<form class="listing-form"
									sort-url="{{ action('TemplateController@sort') }}"
									data-url="{{ action('TemplateController@choosing') }}"
									per-page="{{ Acelle\Model\Template::$itemsPerPage }}"					
								>				
									<div class="row top-list-controls">
										<div class="col-md-9">
											@if (Acelle\Model\Template::getAll()->count() >= 0)					
												<div class="filter-box">													
													<span class="filter-group">
														<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
														<select class="select" name="sort-order">
															<option value="custom_order" class="active">{{ trans('messages.custom_order') }}</option>
															<option value="name">{{ trans('messages.name') }}</option>
															<option value="created_at">{{ trans('messages.created_at') }}</option>
														</select>										
														<button class="btn btn-xs sort-direction" rel="asc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" type="button" class="btn btn-xs">
															<i class="icon-sort-amount-asc"></i>
														</button>
													</span>
													<span class="filter-group">
														<span class="title text-semibold text-muted">{{ trans('messages.from') }}</span>
														<select class="select" name="from">
															<option value="all">{{ trans('messages.all') }}</option>
															<option value="mine" selected='selected'>{{ trans('messages.my_templates') }}</option>
															<option value="gallery">{{ trans('messages.gallery') }}</option>
														</select>										
													</span>
													<span class="text-nowrap">
														<input name="search_keyword" class="form-control search" placeholder="{{ trans('messages.type_to_search') }}" />
														<i class="icon-search4 keyword_search_button"></i>
													</span>
												</div>
												
											@endif
										</div>
										<div class="col-md-3 text-right">
											<a target="_blank" href="{{ action('TemplateController@upload') }}" type="button" class="btn bg-info-800">
												<i class="icon icon-upload"></i> {{ trans('messages.upload_template') }}
											</a>
										</div>
									</div>
									
									<div class="pml-table-container">
										
										
										
									</div>
								</form>
								<br />
							</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-grey" data-dismiss="modal">{{ trans('messages.close') }}</button>
							</div>

					</div>
				</div>
			</div>
			<!-- /choose template  modal -->
				
				
			<form action="{{ action('CampaignController@template', $campaign->uid) }}" method="POST" class="form-validate-jqueryz">
				{{ csrf_field() }}

				<h2 class="mt-0">{{ trans('messages.email_content') }}</h2>
				
				<ul class="nav nav-tabs nav-tabs-top top-divided text-semibold">
					@if ($campaign->type != 'plain-text')
						<li class="active">
							<a href="#top-justified-divided-tab1" data-toggle="tab">
								<i class="icon-circle-code"></i> {{ trans('messages.html_version') }}
							</a>
						</li>
					@endif
					<li class="plain_text_li {{ ($campaign->type == 'plain-text') ? " active" : "" }}">
						<a href="#top-justified-divided-tab2" data-toggle="tab">
							<i class="icon-file-text2"></i> {{ trans('messages.plain_text_version') }}
						</a>
					</li>                       
				</ul>
				
				<div class="tab-content">
					
					@if ($campaign->type != 'plain-text')
						<div class="tab-pane active" id="top-justified-divided-tab1">
							@include('helpers.form_control', ['type' => 'textarea',
																'class' => 'clean-editor',
																'name' => 'html',
																'label' => '',
																'value' => $campaign->html,
																'rules' => $rules,
																'help_class' => 'campaign'
															])
															
							
						</div>
					@endif

					<div class="tab-pane{{ ($campaign->type == 'plain-text') ? " active" : "" }}" id="top-justified-divided-tab2">
						@include('helpers.form_control', ['type' => 'textarea',
															'class' => 'form-control plain_text_content',
															'name' => 'plain',
															'label' => '',
															'value' => $campaign->plain,
															'rules' => $rules,
															'help_class' => 'campaign'
														])
					</div>
				</div>
					
				@if (count($campaign->tags()) > 0)                                
					<div class="tags_list">                                    
						<label class="text-semibold text-teal">{{ trans('messages.required_tags') }}:</label>
						<br />
						@foreach($campaign->tags() as $tag)
							@if ($tag["required"])
								<a data-popup="tooltip" title='{{ trans('messages.click_to_insert_tag') }}' href="javascript:;" class="btn btn-default text-semibold btn-xs insert_tag_button" data-tag-name="{{ "{".$tag["name"]."}" }}">
									{{ "{".$tag["name"]."}" }}
								</a>
							@endif
						@endforeach                                        
					</div>
				@endif
				
				<br />
				@if (count($campaign->tags()) > 0)                                
					<div class="tags_list">                                    
						<label class="text-semibold text-teal">{{ trans('messages.available_tags') }}:</label>
						<br />
						@foreach($campaign->tags() as $tag)
							@if (!$tag["required"])
								<a data-popup="tooltip" title='{{ trans('messages.click_to_insert_tag') }}' href="javascript:;" class="btn btn-default text-semibold btn-xs insert_tag_button" data-tag-name="{{ "{".$tag["name"]."}" }}">
									{{ "{".$tag["name"]."}" }}
								</a>
							@endif
						@endforeach                                        
					</div>
				@endif
				
				<hr>
				<div class="text-right">
					<button class="btn bg-teal-800">{{ trans('messages.next') }} <i class="icon-arrow-right7"></i> </button>
				</div>
				
			<form>
			
			
			
@endsection
