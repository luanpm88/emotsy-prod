@extends('layouts.popup.medium')

@section('content')
	<p class="mb-4">
        <strong>Send-an-email action</strong> Plase folow the wizard below to create email information/content for sending to customers.
        Make sure you fill-up all neccessary information for an email.
    </p>
	
	@include('automation2.email._tabs', ['tab' => 'template'])
	
	<div class="row">
        <div class="col-md-12">
            <h4 class="mb-3 mt-2">{{ trans('messages.automation.choose_your_template_layout') }}</h4>
			<p class="mb-4">{{ trans('messages.automation.choose_your_template_layout.intro') }}</p>
                
            @include('automation2.email.template._tabs', ['tab' => 'theme'])
			
			<div class="template-filter pt-4">
				<div class="d-flex align-items-center">
					<label class="font-weight-semibold mr-2">From</label>
					<select class="select" name="from">
						<option value="all">{{ trans('messages.all') }}</option>
						<option value="mine" selected='selected'>{{ trans('messages.my_templates') }}</option>
						<option value="gallery">{{ trans('messages.gallery') }}</option>
					</select>	
				</div>
			</div>
			<div class="template-list">
				
			</div>
        </div>
    </div>
        
    <script>
		class List {
			constructor(selector, url, data) {
				this.page = 0;
				this.per_page = 4;
				this.list = selector;
				this.loadingHtml = '<div class="list-loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>';
				
				if (url !== null) {
					this.url = url;
				}
				
				this.data = function() { return {}; };
				if (data !== null) {
					this.data = data;
				}
			}
			
			loading() {
				if (!this.list.find('.list-loading').length) {
					this.list.prepend(this.loadingHtml);
				}
				
				this.list.addClass('list-is-loading');
			}
			
			loaded() {
				var _this = this;
				
				// apply js for new content
				initJs(_this.list);
				
				// remove loading effects
				_this.list.find('.list-loading').remove();        
				_this.list.removeClass('list-is-loading');
				
				// page link
				_this.list.find('.page-link').click(function() {
					var page = $(this).attr('data-page');					
					_this.page = page;
					_this.load();
				});
			}
		
			load(url) {
				var _this = this;
        
				if (url != null) {
					_this.url = url;
				}
				
				this.loading();
				
				// post data
				var data = {
					_token: CSRF_TOKEN,
					page: _this.page,
					per_page: _this.per_page,
				};
				
				$.extend( data, _this.data() );
				
				
				$.ajax({
					url: _this.url,
					type: 'POST',
					dataType: 'html',
					data: data,
				}).always(function(response) {
					_this.list.html(response);
					
					// scroll top
					_this.list.animate({scrollTop: 0});
					
					// done
					_this.loaded();
				});
			}
		}
		
		var list = new List(
			$('.template-list'),
			'{{ action('Automation2Controller@templateThemeList', [
				'uid' => $automation->uid,
				'email_uid' => $email->uid,
			]) }}',
			function() {
				return {
					from: $('[name=from]').val(),
				};
			}
		);		
		list.load();
		
		// filters
		$('[name=from]').change(function() {
			list.load();
		});
		
    </script>
@endsection