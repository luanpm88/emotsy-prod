@extends('layouts.frontend')

@section('title', trans('messages.automation.create'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li><a href="{{ action("AutomationController@index") }}">{{ trans('messages.automations') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold">{{ trans('messages.automation.create') }}</span>
				</h1>				
			</div>
@endsection

@section('content')
	<div class="sub-section">
		<div class="automation2-container">
			<button class="btn btn-mc_default add-trigger-but">Click to choose a Trigger</button>
			<br>
			<button class="btn btn-mc_primary trigger mt-20"
				data-type="say-happy-birthday"
			>
					[Trigger] Say `Happy birthday`
			</button>
			<br>
			<button class="btn btn-mc_default add-action-but mt-20"
				data-type="say-happy-birthday"
			>
					Add action
			</button>
			<br>
			<button class="btn btn-mc_primary action mt-20"
				data-type="wait"
			>
					[Action] Wait 30 minutes
			</button>
		</div>
	</div>
		
	<script>
		function SelectActionConfirm(type) {
			var url = '{{ action('Automation2Controller@actionSelectConfirm') }}' + '?type=' + type;
			
			var popup = new Popup(url, function() {
				console.log('Confirm action type popup loaded!');
				
				// set back event
				popup.back = function() {
					OpenActionSelectPopup();
				};
				
				//// when click confirm select trigger type
				//popup.popup.find('.select-trigger-confirm').click(function() {
				//	var url = $(this).attr('data-url');
				//	var data = $('#trigger-select').serialize();
				//	
				//	// show loading effect
				//	popup.loading();
				//	
				//	$.ajax({
				//		url: url,
				//		type: 'POST',
				//		data: data,
				//	}).always(function(response) {
				//		// todo: when trigger selected
				//		console.log('Trigger was selected');
				//		
				//		popup.hide();
				//		
				//		notify('success', '{{ trans('messages.notify.success') }}', response.message);
				//	});
				//});
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
