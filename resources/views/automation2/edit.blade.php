@extends('layouts.automation.main')

@section('title', trans('messages.automation.create'))

@section('content')
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
        text:hover {
            fill: pink;
        }

        rect.selected {
            stroke-width: 1 !important;;
            stroke-dasharray: 5;
        }

        rect.element {
            stroke:black;
            stroke-width:0;
        }

        rect.action {
            fill: rgb(101, 117, 138);
        }

        rect.trigger {
            fill: rgba(12, 12, 12, 0.49);
        }

        rect.wait {
            fill: rgb(91, 156, 103);
        }

        rect.condition {
            fill: #ee8d50;
        }
    </style>
	
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
				<a href="{{ action('Automation2Controller@index') }}" class="action">
					{{ trans('messages.automation.go_back') }}
				</a>
				<a href="javascript:'" class="action">
					<i class="lnr lnr-menu mr-2"></i>
					{{ trans('messages.automation.switch_automation') }}
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
				<svg id="svg" style="overflow: auto" width="100%" height="800" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"></svg>
			</div>
			<div class="sidebar">
				
			</div>
		</div>
	</main>
		
	<script>
		var popup = new Popup();
		var sidebar = new Box($('.sidebar'));
		
		function setAutomationName(name) {
			$('.navbar h1').html(name);
		}
		
		function saveData() {
			var url = '{{ action('Automation2Controller@saveData', $automation->uid) }}';
		
			$.ajax({
				url: url,
				type: 'POST',
				data: {
					_token: CSRF_TOKEN,
					data: tree.toJson(),
				},
			}).always(function() {
			});
		}
		
		function SelectActionConfirm(type) {
			var url = '{{ action('Automation2Controller@actionSelectConfirm', $automation->uid) }}' + '?type=' + type;
			
			popup = new Popup(url, function() {
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
						if (response.options.type == 'wait') {
							var newE = new ElementWait({title: response.title, options: response.options});
						} else if (response.options.type == 'condition') {
							var newE = new ElementCondition({title: response.title, options: response.options});
						}
						tree.getSelected().insert(newE);
						
						// save tree
						saveData();
						
						popup.hide();
						
						notify('success', '{{ trans('messages.notify.success') }}', response.message);
					});
				});
			});
			
			popup.load();
		}
	
		function OpenActionSelectPopup() {
			popup = new Popup('{{ action('Automation2Controller@actionSelectPupop', $automation->uid) }}', function() {
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
			popup = new Popup('{{ action('Automation2Controller@triggerSelectPupop', $automation->uid) }}', function() {
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
			var url = '{{ action('Automation2Controller@triggerSelectConfirm', $automation->uid) }}' + '?type=' + type;
			
			popup = new Popup(url, function() {
				console.log('Confirm trigger type popup loaded!');
				
				// set back event
				popup.back = function() {
					OpenTriggerSelectPopup();
				};
				
				// when click confirm select trigger type
				popup.popup.find('.select-trigger-confirm').click(function(e) {
					e.preventDefault();
				
					var url = $(this).attr('data-url');
					var data = $(this).closest('form').serialize();
					
					// show loading effect
					popup.loading();
					
					$.ajax({
						url: url,
						type: 'POST',
						data: data,
					}).always(function(response) {
						// todo: when trigger selected
						// console.log('Trigger was selected');
						
						// set node title
						tree.setTitle(response.title);
						// merge options with reponse options
						tree.setOptions($.extend(tree.getOptions(), response.options));
						tree.setOptions($.extend(tree.getOptions(), {init: "true"}));
						
						// save tree
						saveData();
						
						// hide popup
						popup.hide();
						
						// notify success message
						notify('success', '{{ trans('messages.notify.success') }}', response.message);
						
						// Edit Trigger
						EditTrigger('{{ action('Automation2Controller@triggerEdit', $automation->uid) }}' + '?type=' + tree.getOptions().type);
					});
				});
			});
			
			popup.load();
		}
		
		function EditTrigger(url) {
			sidebar.load(url);
		}
		
		function EditAction(url) {
			sidebar.load(url);
		}
	
		$(document).ready(function() {
			// load sidebar
			sidebar.load('{{ action('Automation2Controller@settings', $automation->uid) }}');
		
			// Click on add trigger button
			$(document).on('click', '.add-trigger-but', function() {
				console.log('add trigger click!');
				
				// Open trigger types select list
				OpenTriggerSelectPopup();
			});
			
			//// Click on exist trigger
			//$(document).on('click', '.trigger', function() {			
			//	var type = $(this).attr('data-type');				
			//	var url = '{{ action('Automation2Controller@triggerEdit', $automation->uid) }}' + '?type=' + type;
			//	
			//	// Open trigger types select list
			//	EditTrigger(url);
			//});
			
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
				var url = '{{ action('Automation2Controller@actionEdit', $automation->uid) }}' + '?type=' + type + '&id=' + tree.getSelected().id;
				
				// Open trigger types select list
				EditAction(url);
			});
			
			// Click on exist action
			$(document).on('click', '.action-email', function() {
				console.log('click on one action!');
				
				var url = $(this).attr('data-url');				
				var container = $('.sidebar-setting-container');
			
				$.ajax({
					url: url,
					type: 'GET',
				}).always(function(response) {
					// todo: when trigger selected
					console.log('Action was selected');
					
					container.html(response);
					
					// init js
					initJs(container);
				});
			});
		});
	</script>
		
	<script>
        var tree;

        (function() {
            //var json = [
            //    {title: "Click to choose a trigger", id: "trigger", type: "ElementTrigger", options: {init: false}}
            //];
			
			@if ($automation->data)
				var json = {!! $automation->getData() !!};
			@else
				var json = [
					{title: "Click to choose a trigger", id: "trigger", type: "ElementTrigger", options: {init: "false"}}
				];
			@endif

            var container = document.getElementById('svg');

            tree = AutomationElement.fromJson(json, container, {
                onclick: function(e) {				
                    // TODO 1:
                    // Gọi Ajax to Automation2@action
                    // Prams: e.getId()
                    // Trả về thông tin chi tiết của action để load nội dung bên phải
                    // Trên server: gọi hàm model: Automation2::getActionInfo(id)
					
                    e.select(); // highlight
					
					console.log(e.getType());
                    
					// if click on a trigger
					if (e.getType() == 'ElementTrigger') {
						var options = e.getOptions();
						
						// check if trigger is not init
						if (options.init == "false") {
							OpenTriggerSelectPopup();
						}
						// trigger was init
						else {
							var type = $(this).attr('data-type');
							var url = '{{ action('Automation2Controller@triggerEdit', $automation->uid) }}' + '?type=' + e.getOptions().type + '&id=' + e.getId();
							
							// Open trigger types select list
							EditTrigger(url);
						}
					}
					// is WAIT
					else if (e.getType() == 'ElementWait') {
							var type = $(this).attr('data-type');
							var url = '{{ action('Automation2Controller@actionEdit', $automation->uid) }}' + '?type=' + e.getOptions().type + '&id=' + e.getId();
							
							// Open trigger types select list
							EditAction(url);
					}
					// is Condition
					else if (e.getType() == 'ElementCondition') {
							var type = $(this).attr('data-type');
							var url = '{{ action('Automation2Controller@actionEdit', $automation->uid) }}' + '?type=' + e.getOptions().type + '&id=' + e.getId();
							
							// Open trigger types select list
							EditAction(url);
					}
					// is Condition
					else if (e.getType() == 'ElementAction') {
							var type = $(this).attr('data-type');
							var url = e.getOptions().edit_url;
							
							// Open trigger types select list
							EditAction(url);
					}
                },

                onhover: function(e) {
                    console.log(e.title + " hovered!");
                },

                onadd: function(e) {
					e.select();
                    // TODO 3: hiện popup cho user chọn type của action
                    // var title = prompt('Enter new element name to insert');
                    // ..... popup....

                    // sau khi chọn và cấu hình xong xuôi hết
					//  e.insert(new ElementAction({title: title}));
                    // OR e.insert(new ElementWait({title: title}));
                    // OR e.insert(new ElementCondition({title: title}));
					
					OpenActionSelectPopup();
                }
            });

        })();
    </script>
@endsection
