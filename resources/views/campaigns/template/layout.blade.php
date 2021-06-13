@extends('layouts.popup.large')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h2>{{ trans('messages.campaign.choose_your_template_layout') }}</h2>
                
            <ul class="nav nav-tabs mc-nav campaign-template-tabs">
                <li class="active"><a href="{{ action('CampaignController@templateLayout', $campaign->uid) }}">Layouts</a></li>
                <li><a href="{{ action('CampaignController@templateTheme', $campaign->uid) }}">Themes</a></li>
                <li><a href="{{ action('CampaignController@templateUpload', $campaign->uid) }}">Upload</a></li>
            </ul>

            @foreach (Acelle\Model\TemplateCategory::all() as $category)
				@if ($category->templates()->count())
					<div class="subsection pb-4">
						<h2 class="font-weight-semibold mb-0">{{ $category->name }}</h2>
						<hr>

						<div id="gallery" class="pb-4">
							<form class="listing-form"
                                data-url="{{ action('CampaignController@templateLayoutList', [
                                    'uid' => $campaign->uid,
									'category_uid' => $category->uid,
								]) }}"
								per-page="25"					
							>				
								<div class="row top-list-controls">
									<div class="col-md-9">
										<div class="filter-box">
											<span class="filter-group">
												<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
												<select class="select" name="sort-order">
													<option value="id">{{ trans('messages.default') }}</option>
													<option value="created_at">{{ trans('messages.created_at') }}</option>
													<option value="custom_order" class="active">{{ trans('messages.custom_order') }}</option>
													<option value="name">{{ trans('messages.name') }}</option>
												</select>										
												<button class="btn btn-xs sort-direction" rel="asc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" type="button" class="btn btn-xs">
													<i class="icon-sort-amount-asc"></i>
												</button>
											</span>
											<span class="text-nowrap">
												<input name="search_keyword" class="form-control search" placeholder="{{ trans('messages.type_to_search') }}" />
												<i class="icon-search4 keyword_search_button"></i>
											</span>
										</div>
									</div>
								</div>
								
								<div class="pml-table-container">
								</div>
							</form>
						</div>
						<br style="clear:both" /><br style="clear:both" />
					</div>
				@endif
			@endforeach
        </div>
    </div>
        
    <script>
        $('.campaign-template-tabs a').click(function(e) {
            e.preventDefault();
        
            var url = $(this).attr('href');
        
            templatePopup.load(url);
        });
        
        var builderSelectPopup = new Popup(null, undefined, {onclose: function() {
            window.location = '{{ action('CampaignController@template', $campaign->uid) }}';
        }});
    </script>
@endsection