@extends('layouts.core.backend')

@section('title', trans('messages.plugins'))

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
			<span class="text-semibold"><i class="icon-list2"></i> {{ trans('messages.plugins') }}</span>
		</h1>
	</div>

@endsection

@section('content')
	<p>{{ trans('messages.plugin.wording') }}</p>

	<form class="listing-form"
		sort-url="{{ action('Admin\PluginController@sort') }}"
		data-url="{{ action('Admin\PluginController@listing') }}"
		per-page="{{ Acelle\Model\Plugin::$itemsPerPage }}"
	>
		<div class="row top-list-controls py-2">
			<div class="col-md-10">
				@if ($plugins->count() >= 0)
					<div class="filter-box">
						<div class="dropdown list_actions" style="display: none">
							<button role="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
								{{ trans('messages.actions') }} <span class="number"></span><span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a link-confirm="{{ trans('messages.enable_plugins_confirm') }}" href="{{ action('Admin\PluginController@enable') }}"><span class="material-icons-outlined">
play_arrow
</span> {{ trans('messages.enable') }}</a></li>
								<li><a link-confirm="{{ trans('messages.disable_plugins_confirm') }}" href="{{ action('Admin\PluginController@disable') }}"><span class="material-icons-outlined">
hide_source
</span> {{ trans('messages.disable') }}</a></li>
								<li><a link-confirm="{{ trans('messages.delete_plugins_confirm') }}" href="{{ action('Admin\PluginController@delete') }}"><span class="material-icons-outlined">
delete_outline
</span> {{ trans('messages.delete') }}</a></li>
							</ul>
						</div>
						<span class="text-nowrap">
							<input type="text" name="keyword" class="form-control search" placeholder="{{ trans('messages.type_to_search') }}" />
							<span class="material-icons-round">
search
</span>
						</span>
					</div>
				@endif
			</div>
			<div class="col-md-2 text-end">
				<a href="{{ action("Admin\PluginController@install") }}" role="button" class="btn btn-secondary">
					<span class="material-icons-round">
add
</span> {{ trans('messages.install_plugin') }}
				</a>
			</div>
		</div>

		<div class="pml-table-container">
		</div>
	</form>
@endsection
