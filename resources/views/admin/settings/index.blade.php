@extends('layouts.backend')

@section('title', trans('messages.settings'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
		
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>		
@endsection

@section('page_header')

			<div class="page-title">				
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
				</ul>
				<h1>
					<span class="text-gear"><i class="icon-list2"></i> {{ trans('messages.settings') }}</span>
				</h1>				
			</div>

@endsection

@section('content')
			<form action="{{ action('Admin\SettingController@index') }}" method="POST" class="form-validate-jqueryz">
				{{ csrf_field() }}
				
				<div class="tabbable">
					<ul class="nav nav-tabs nav-tabs-top">
						@if (Auth::user()->getOption("backend", "setting_general") == 'yes')
							<li class="active text-semibold"><a href="#top-tab1" data-toggle="tab">
								<i class="icon-equalizer2"></i> {{ trans('messages.general') }}</a></li>
						@endif
						@if (Auth::user()->getOption("backend", "setting_sending") == 'yes')
							<li class="text-semibold"><a href="#top-tab2" data-toggle="tab">
								<i class="icon-paperplane"></i> {{ trans('messages.sending') }}</a></li>
						@endif
						@if (Auth::user()->getOption("backend", "setting_system_urls") == 'yes')
							<li class="text-semibold"><a href="#top-tab3" data-toggle="tab">
								<i class="icon-link"></i> {{ trans('messages.system_urls') }}</a></li>
						@endif
					</ul>
	
					<div class="tab-content">
						@if (Auth::user()->getOption("backend", "setting_general") == 'yes')
							<div class="tab-pane active" id="top-tab1">
								<div class="row">
									<?php $count = 0; ?>
									@foreach ($settings as $name => $setting)
										@if ($setting['cat'] == 'general')
											<div class="col-md-4">
												@if ($setting['type'] == 'checkbox')
													<div class="form-group checkbox-right-switch">
												@endif
													@include('helpers.form_control', [
														'type' => $setting['type'],
														'class' => (isset($setting['class']) ? $setting['class'] : "" ),
														'name' => $name,
														'value' => $setting['value'],
														'label' => trans('messages.' . $name),
														'help_class' => 'setting',
														'options' => (isset($setting['options']) ? $setting['options'] : "" ),
														'rules' => Mailixa\Model\Setting::rules(),
													])
												@if ($setting['type'] == 'checkbox')
													</div>
												@endif
											</div>
											@if ($count%3 == 2)
								</div><div class="row">
											@endif
											<?php $count++; ?>
										@endif
									@endforeach							
								</div>
							</div>
						@endif
						@if (Auth::user()->getOption("backend", "setting_sending") == 'yes')
							<div class="tab-pane" id="top-tab2">
								<div class="row">
									<?php $count = 0; ?>
									@foreach ($settings as $name => $setting)
										@if ($setting['cat'] == 'sending')
											<div class="col-md-4">
												@if ($setting['type'] == 'checkbox')
													<div class="form-group checkbox-right-switch">
												@endif
													@include('helpers.form_control', [
														'type' => $setting['type'],
														'class' => (isset($setting['class']) ? $setting['class'] : "" ),
														'name' => $name,
														'value' => $setting['value'],
														'label' => trans('messages.' . $name),
														'help_class' => 'setting',
														'options' => (isset($setting['options']) ? $setting['options'] : "" ),
														'rules' => Mailixa\Model\Setting::rules(),
													])
												@if ($setting['type'] == 'checkbox')
													</div>
												@endif
											</div>
											@if ($count%3 == 2)
								</div><div class="row">
											@endif
											<?php $count++; ?>
										@endif
									@endforeach							
								</div>
							</div>
						@endif
						@if (Auth::user()->getOption("backend", "setting_system_urls") == 'yes')
							<div class="tab-pane" id="top-tab3">
								<div class="">
									<ul class="modern-listing mt-0 top-border-none">
										@foreach ($settings as $name => $setting)
											@if ($setting['cat'] == 'url')
												<li>
													<i class="icon-link text-grey"></i>
													<h5 class="mt-0 mb-0 text-semibold">
														{!! str_replace(trans("messages.MESSAGE_ID"), "<span class='text-info-600'>" . trans("messages.MESSAGE_ID") . "</span>", str_replace(trans("messages.URL"), "<span class='text-info-600'>" . trans("messages.URL") . "</span>", $setting['value'])) !!}
													</h5>
													<p>
														{{ trans('messages.' . $name) }}
													</p>
												</li>
											@endif
										@endforeach
									</ul>
								</div>
							</div>
						@endif
						
					</div>
				</div>
					
				<br />
				<div class="text-left">
					<button class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
				</div>
			</form>
				
			<script>
				function changeSelectColor() {
					$('.select2 .select2-selection__rendered, .select2-results__option').each(function() {							
						var text = $(this).html();
						if (text == '{{ trans('messages.default') }}') {
							if($(this).find("i").length == 0) {
								$(this).prepend("<i class='icon-square text-teal-600'></i>");
							}
						}
						if (text == '{{ trans('messages.blue') }}') {
							if($(this).find("i").length == 0) {
								$(this).prepend("<i class='icon-square text-blue'></i>");
							}
						}
						if (text == '{{ trans('messages.green') }}') {
							if($(this).find("i").length == 0) {
								$(this).prepend("<i class='icon-square text-green'></i>");
							}
						}
						if (text == '{{ trans('messages.brown') }}') {
							if($(this).find("i").length == 0) {
								$(this).prepend("<i class='icon-square text-brown'></i>");
							}
						}
						if (text == '{{ trans('messages.pink') }}') {
							if($(this).find("i").length == 0) {
								$(this).prepend("<i class='icon-square text-pink'></i>");
							}
						}
						if (text == '{{ trans('messages.grey') }}') {
							if($(this).find("i").length == 0) {
								$(this).prepend("<i class='icon-square text-grey'></i>");
							}
						}
						if (text == '{{ trans('messages.white') }}') {
							if($(this).find("i").length == 0) {
								$(this).prepend("<i class='icon-square text-white'></i>");
							}
						}
					});
				}
				
				$(document).ready(function() {
					setInterval("changeSelectColor()", 100);
				});
			</script>
@endsection
