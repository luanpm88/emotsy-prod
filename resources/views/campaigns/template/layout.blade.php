@extends('layouts.popup.large')

@section('title')
    <i class="material-icons-outlined alert-icon mr-2">backup_table</i>
    {{ trans("messages.template") }}
@endsection

@section('content')
	<div class="row">
        <div class="col-md-12">
            <h4 class="">{{ trans('messages.campaign.choose_your_template_layout') }}</h4>
			@include('campaigns.template._tabs')
			<div class="tab-content" id="pills-tabContent">
				<div class="">
					<div class="subsection pb-4">
						<h2 class="font-weight-semibold mb-0">
							@if (request()->category_uid)
								{{ Acelle\Model\TemplateCategory::findByUid(request()->category_uid)->name }}
							@elseif (request()->from == 'mine')
								{{ trans('messages.my_templates') }}
							@endif
						</h2>
						<hr>

						<div id="gallery" class="pb-4">
							<div class="listing-form">				
								<div class="row top-list-controls py-2">
									<div class="col-md-9">
										<div class="filter-box">{{ request()->from }}
											<span class="d-flex align-items-center mr-4">
												<span class="title text-semibold text-muted mr-2">{{ trans('messages.sort_by') }}</span>
												<select class="select mr-3" name="sort_order">
													<option value="id">{{ trans('messages.default') }}</option>
													<option value="created_at">{{ trans('messages.created_at') }}</option>
													<option value="name">{{ trans('messages.name') }}</option>
												</select>					
												<input type="hidden" name="sort_direction" value="desc" />	
												<input type="hidden" name="category_uid" value="{{ request()->category_uid }}" />				
												<button class="btn btn-xs sort-direction mr-3 ml-2" rel="asc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
													<span class="material-icons-outlined desc">
sort
</span>
												</button>
												<span class="title text-semibold text-muted mr-2">{{ trans('messages.from') }}</span>
												<select class="select" name="from">
													<option value="all" selected='selected'>{{ trans('messages.all') }}</option>
													<option {{ request()->from == 'mine' ? 'selected' : '' }} value="mine">{{ trans('messages.my_templates') }}</option>
													<option value="gallery">{{ trans('messages.gallery') }}</option>
												</select>
												<input name="keyword" style="margin-left: 15px" class="form-control search" placeholder="{{ trans('messages.type_to_search') }}" />
												<span class="material-icons-round">
search
</span>
											</span>
										</div>
									</div>
								</div>
								
								<div id="galleryContent" class="pml-table-container">
								</div>
							</div>
						</div>
						<br style="clear:both" /><br style="clear:both" />
					</div>
				</div>
			</div>
        </div>
    </div>
        
    <script>
		var CampaignsTemplateLayout = {
			listUrl: '{{ action('CampaignController@templateLayoutList', [
				'uid' => $campaign->uid,
			]) }}',

			getList: function() {
				return makeList({
					url: this.listUrl,
					container: $('#gallery'),
					content: $('#galleryContent')
				});
			}
		}

		$(document).ready(function() {
			//load list
			CampaignsTemplateLayout.getList().load();

            $('a.choose-template-tab').click(function(e) {
				e.preventDefault();
			
				var url = $(this).attr('href');
			
				templatePopup.load(url);
			});
        });
		
		// legacy
        var builderSelectPopup = new Popup({
			onclose: function() {
				window.location = '{{ action('CampaignController@template', $campaign->uid) }}';
			}
		});
    </script>
@endsection