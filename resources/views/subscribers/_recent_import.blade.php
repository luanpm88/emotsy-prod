            <hr>
			<h2 class="text-bold text-teal-800"><i class="icon-task"></i> {{ trans('messages.recent_import') }}</h2>
			<form class="listing-form"
					data-url="{{ action('SystemJobController@listing', ["type" => "import-subscriber"]) }}"
					per-page="{{ Acelle\Model\SystemJob::$itemsPerPage }}"					
				>				
					<div class="row top-list-controls">
						<div class="col-md-10">
							@if ($system_jobs->count() >= 0)					
								<div class="filter-box">
									<!--<div class="btn-group list_actions hide">
										<button type="button" class="btn btn-xs btn-grey-600 dropdown-toggle" data-toggle="dropdown">
											{{ trans('messages.actions') }} <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">											
											<li><a delete-confirm="{{ trans('messages.delete_system_jobs_confirm') }}" href="{{ action('SystemJobController@delete') }}"><i class="icon-trash"></i> {{ trans('messages.delete') }}</a></li>
										</ul>
									</div>
									<div class="checkbox inline check_all_list">
										<label>
											<input type="checkbox" class="styled check_all">
										</label>
									</div>-->
									<span class="filter-group">
										<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
										<select class="select" name="sort-order">
											<option value="system_jobs.created_at">{{ trans('messages.created_at') }}</option>
											<option value="system_jobs.updated_at">{{ trans('messages.updated_at') }}</option>
										</select>										
										<button class="btn btn-xs sort-direction" rel="desc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" type="button" class="btn btn-xs">
											<i class="icon-sort-amount-desc"></i>
										</button>
									</span>									
								</div>
							@endif
						</div>
						<!--<div class="col-md-2 text-right">
							<a href="{{ action("SegmentController@create", $list->uid) }}" type="button" class="btn bg-info-800">
								<i class="icon icon-plus2"></i> {{ trans('messages.clear_all') }}
							</a>
						</div>-->
					</div>
					
					<div class="pml-table-container">
						
						
						
					</div>
				</form>
					
				<script>
					$(document).ready(function() {
						setInterval('tableFilterAll()', 3000);
					});
				</script>