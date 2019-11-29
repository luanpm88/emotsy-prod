@extends('layouts.automation.main')

@section('title', trans('messages.automation.create'))

@section('content')
	<header>
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
			<a class="navbar-brand left-logo" href="#">
				@if (\Acelle\Model\Setting::get('site_logo_small'))
					<img src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" alt="">
				@else
					<img height="22" src="{{ URL::asset('images/logo_light.png') }}" alt="">
				@endif
			</a>
			<h1 class="mr-auto">{{ $automation->name }}</h1>
			<div class="automation-top-menu">
				<span class="mr-3"><i>Last saved: 2 minutes</i></span>
				<a href="javascript:'" class="action">Go back</a>
				<a href="javascript:'" class="action">
					<i class="lnr lnr-menu mr-2"></i>
					Switch Automation
				</a>
				<div class="account-info">
					<a href="">
						<img class="avatar" src="{{ action('CustomerController@avatar', Auth::user()->customer->uid) }}" alt="">
						{{ Auth::user()->customer->displayName() }}
					</a>
				</div>
			</div>
		</nav>
	</header>
	
	<main role="main">
		<div class="automation2">
			<div class="diagram text-center">
				<button class="btn btn-secondary add-trigger-but mb-4">Click to choose a Trigger</button>
				<br>
				<button class="btn btn-primary trigger mb-4"
					data-type="say-happy-birthday"
				>
						[Trigger] Say `Happy birthday`
				</button>
				<br>
				<button class="btn btn-secondary add-action-but mb-4"
					data-type="say-happy-birthday"
				>
						Add action
				</button>
				<br>
				<button class="btn btn-danger action mb-4"
					data-type="wait"
				>
						[Action] Wait 30 minutes
				</button>
			</div>
			<div class="sidebar">
				<div class="sidebar-header flex-center mb-3">
					<h5 class="m-0 mr-auto">{{ $automation->name }}</h5>
					<span class="mr-2 small"><i>Running</i></span>
					<div>
						@include('helpers.form_control', [
							'type' => 'checkbox',
							'name' => 'sign_dkim',
							'label' => '',
							'value' => 'true',
							'options' => [false,true],
							'help_class' => '',
							'rules' => []
						])
					</div>
				</div>
				<p>This is an automation for the poorest marketers. Triggered when people subscribe to your mail list “<strong>La Paloma</strong>”</p>
				
				
				
				<ul class="nav nav-tabs mt-5">
					<li class="nav-item">
						<a class="nav-link active" href="#">
							<i class="lnr lnr-exit-up mr-2"></i>
							Trigger
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="lnr lnr-layers mr-2"></i>
							Information
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="lnr lnr-cog mr-2"></i>
							Settings
						</a>
					</li>
				</ul>
					
				<h5 class="mb-3 mt-4">
					{{ trans('messages.automation.trigger.say-happy-birthday') }}
				</h5>
				<p class="">
					{{ trans('messages.automation.trigger.say-happy-birthday.intro') }}
				</p>
				
				<div class="mt-4">
					<button class="btn btn-secondary mr-2">Save</button>
					<button class="btn btn-outline-secondary">Chage Trigger</button>
				</div>
				<br><br><br>
				<h6 class="mb-2 mt-5 text-danger">
					Dangerous Zone
				</h6>
				<p class="">
					Delete this action will also delete all its children. This action cannot be undone
				</p>
				
				<div class="mt-3">
					<button class="btn btn-danger">
						<i class='lnr lnr-trash mr-2'></i> Delete This Action</button>
				</div>
			</div>
		</div>
	</main>
		
	<script>
		function SelectActionConfirm(type) {
			var url = '{{ action('Automation2Controller@actionSelectConfirm') }}' + '?type=' + type;
			
			var popup = new Popup(url, function() {
				console.log('Confirm action type popup loaded!');
				
				// set back event
				popup.back = function() {
					OpenActionSelectPopup();
				};
				
				// when click confirm select trigger type
				popup.popup.find('.select-action-confirm').click(function(e) {
					e.preventDefault();
				
					var url = $(this).attr('data-url');
					var data = $('#action-select').serialize();
					
					// show loading effect
					popup.loading();
					
					$.ajax({
						url: url,
						type: 'POST',
						data: data,
					}).always(function(response) {
						// todo: when trigger selected
						console.log('Action was selected');
						
						popup.hide();
						
						notify('success', '{{ trans('messages.notify.success') }}', response.message);
					});
				});
			});
			
			popup.load();
		}
	
		function OpenActionSelectPopup() {
			var popup = new Popup('{{ action('Automation2Controller@actionSelectPupop') }}', function() {
				console.log('Select action popup loaded!');
				
				// set back event
				popup.back = function() {
					Popup.hide();
				};
				
				// when click on action type
				popup.popup.find('.action-select-but').click(function() {
					var type = $(this).attr('data-type');
					
					// show select trigger confirm box
					SelectActionConfirm(type);
				});
			});
			
			popup.load();
		}
		
		function OpenTriggerSelectPopup() {
			var popup = new Popup('{{ action('Automation2Controller@triggerSelectPupop') }}', function() {
				console.log('Select trigger popup loaded!');
				
				// set back event
				popup.back = function() {
					Popup.hide();
				};
				
				// when click on trigger type
				popup.popup.find('.trigger-select-but').click(function() {
					var type = $(this).attr('data-type');
					
					// show select trigger confirm box
					SelectTriggerConfirm(type);
				});
			});
			
			popup.load();
		}
		
		function SelectTriggerConfirm(type) {
			var url = '{{ action('Automation2Controller@triggerSelectConfirm') }}' + '?type=' + type;
			
			var popup = new Popup(url, function() {
				console.log('Confirm trigger type popup loaded!');
				
				
				
				// set back event
				popup.back = function() {
					OpenTriggerSelectPopup();
				};
				
				// when click confirm select trigger type
				popup.popup.find('.select-trigger-confirm').click(function() {
					var url = $(this).attr('data-url');
					var data = $('#trigger-select').serialize();
					
					// show loading effect
					popup.loading();
					
					$.ajax({
						url: url,
						type: 'POST',
						data: data,
					}).always(function(response) {
						// todo: when trigger selected
						console.log('Trigger was selected');
						
						popup.hide();
						
						notify('success', '{{ trans('messages.notify.success') }}', response.message);
					});
				});
			});
			
			popup.load();
		}
	
		$(document).ready(function() {
			// Click on add trigger button
			$(document).on('click', '.add-trigger-but', function() {
				console.log('add trigger click!');
				
				// Open trigger types select list
				OpenTriggerSelectPopup();
			});
			
			// Click on exist trigger
			$(document).on('click', '.trigger', function() {
				console.log('click on one trigger!');
				
				var type = $(this).attr('data-type');
				
				// Open trigger types select list
				SelectTriggerConfirm(type);
			});
			
			// click on add action button
			$(document).on('click', '.add-action-but', function() {
				console.log('add action click!');
				
				// Open action types select list
				OpenActionSelectPopup();
			});
			
			// Click on exist action
			$(document).on('click', '.action', function() {
				console.log('click on one action!');
				
				var type = $(this).attr('data-type');
				
				// Open action types select list
				SelectActionConfirm(type);
			});
		});
	</script>
@endsection
