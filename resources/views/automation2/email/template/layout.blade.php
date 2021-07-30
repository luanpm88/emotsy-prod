@extends('layouts.popup.large')

@section('content')
	
	@include('automation2.email._tabs', ['tab' => 'template'])
		
	<div class="row">
        <div class="col-md-12">
            <h5 class="mb-3">{{ trans('messages.automation.choose_your_template_layout') }}</h5>
			<p class="mb-4">{{ trans('messages.automation.choose_your_template_layout.intro') }}</p>
                
            @include('automation2.email.template._tabs')

			<h2 class="font-weight-semibold mb-0">
				@if (request()->category_uid)
					{{ Acelle\Model\TemplateCategory::findByUid(request()->category_uid)->name }}
				@elseif (request()->from == 'mine')
					{{ trans('messages.my_templates') }}
				@endif
			</h2>

			<div class="template-filter filter-box pt-4">
				<div class="d-flex align-items-center">
					<input type="hidden" name="category_uid" value="{{ request()->category_uid }}" />
					<label class="font-weight-semibold mr-2">From</label>
					<select class="select" name="from">
						<option value="all" selected='selected'>{{ trans('messages.all') }}</option>
						<option {{ request()->from == 'mine' ? 'selected' : '' }} value="mine">{{ trans('messages.my_templates') }}</option>
						<option value="gallery">{{ trans('messages.gallery') }}</option>
					</select>	
				</div>
			</div>
			<div class="template-list ajax-list">
				
			</div>
        </div>
    </div>
        
    <script>
		var listTheme = makeList({
            url: '{{ action('Automation2Controller@templateLayoutList', [
				'uid' => $automation->uid,
				'email_uid' => $email->uid,
			]) }}',
            container: $('.template-list'),
            content: $('.template-list'),
            data: function() {
                return {
                    from: $('[name=from]').val(),
					sort_direction: 'asc',
					sort_order: 'id',
                    per_page: 12
                };
            }
        });
		
		listTheme.load();
		
		// filters
		$('[name=from]').change(function() {
			listTheme.load();
		});
    </script>
@endsection