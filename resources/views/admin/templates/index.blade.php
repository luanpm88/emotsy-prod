@extends('layouts.backend')

@section('title', trans('messages.templates'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>

	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection

@section('page_header')

			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-list2"></i> {{ trans('messages.template_gallery') }}</span>
				</h1>
			</div>

@endsection

@section('content')
	<p>{{ trans('messages.templates.admin.intro') }}</p>
				<form class="listing-form"
					data-url="{{ action('Admin\TemplateController@listing') }}"
					per-page="{{ Acelle\Model\Template::$itemsPerPage }}"
				>
					<div class="row top-list-controls py-2">
						<div class="col-md-9">
							<div class="filter-box">
								<div class="btn-group list_actions hide">
									<button role="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
										{{ trans('messages.actions') }} <span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										<li>
											<a delete-confirm="{{ trans('messages.delete_templates_confirm') }}" href="{{ action('Admin\TemplateController@delete') }}">
												<span class="material-icons-outlined">
delete_outline
</span> {{ trans('messages.delete') }}
											</a>
										</li>
									</ul>
								</div>
								<div class="checkbox inline check_all_list">
									<label>
										<input type="checkbox" name="page_checked" class="styled check_all">
									</label>
								</div>
								<span class="filter-group">
									<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
									<select class="select" name="sort_order">
										<option value="created_at">{{ trans('messages.created_at') }}</option>
										<option value="name">{{ trans('messages.name') }}</option>
									</select>
									<button class="btn btn-xs sort-direction" rel="desc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
										<span class="material-icons-outlined desc">
sort
</span>
									</button>
								</span>
								<span class="filter-group">
									<select class="select" name="category_uid">
										<option value="">{{ trans('messages.template.all_categories') }}</option>
										@foreach (\Acelle\Model\TemplateCategory::all() as $category)
											<option value="{{ $category->uid }}">{{ $category->name }}</option>
										@endforeach
									</select>
								</span>
								<span class="text-nowrap">
									<input type="text" name="keyword" class="form-control search" placeholder="{{ trans('messages.type_to_search') }}" />
									<span class="material-icons-round">
search
</span>
								</span>
							</div>
						</div>
						<div class="col-md-3 text-end d-flex align-items-center">
							<a href="{{ action('Admin\TemplateController@uploadTemplate') }}" class="btn btn-secondary mr-2 ml-auto">
								<span class="material-icons-outlined">
file_upload
</span> {{ trans('messages.upload') }}
							</a>
							<a href="{{ action('Admin\TemplateController@builderCreate') }}" class="btn btn-secondary">
								<span class="material-icons-round">
add
</span> {{ trans('messages.create') }}
							</a>
						</div>
					</div>

					<div class="pml-table-container">



					</div>
				</form>

@endsection
