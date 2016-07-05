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
						<li class="active text-semibold"><a href="#top-tab1" data-toggle="tab">
							<i class="icon-equalizer2"></i> {{ trans('messages.general') }}</a></li>
						<li class="text-semibold"><a href="#top-tab2" data-toggle="tab">
							<i class="icon-paperplane"></i> {{ trans('messages.sending') }}</a></li>
						<li class="text-semibold"><a href="#top-tab3" data-toggle="tab">
							<i class="icon-link"></i> {{ trans('messages.system_urls') }}</a></li>
					</ul>
	
					<div class="tab-content">
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
						<div class="tab-pane" id="top-tab3">
							<div class="">
								<ul class="modern-listing mt-0 top-border-none">
									@foreach ($settings as $name => $setting)
										@if ($setting['cat'] == 'url')
											<li>
												<i class="icon-cancel-circle2 text-danger"></i>
												<h5 class="mt-0 mb-0 text-semibold">{{ $setting['value'] }}</h5>
												<p>
													{{ trans('messages.' . $name) }}
												</p>
											</li>
										@endif
									@endforeach
								</ul>
							</div>
						</div>	
						
					</div>
				</div>
					
				<hr >
				<div class="text-left">
					<button class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
				</div>
			</form>
@endsection
