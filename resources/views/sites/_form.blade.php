<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label class="fw-semibold">{{ trans('messages.site.name') }}</label>
			@include('helpers.form_control.text', [
				'name' => 'site[name]',
				'value' => $site->name,
				'attributes' => [
					'required' => 'required',
				],
			])
			<span class="d-block small fst-italic text-muted mt-1">
				{{ trans('messages.site.name_just_internal_use') }}</span>
		</div>
		
		<div class="form-group">
			<label class="fw-semibold mb-2">{{ trans('messages.site.type') }}</label>
			<div>
				<div data-control="option-container" class="d-flex">
					<label class="me-3">
						<input checked type="radio" name="site[type]" value="1" id="udesfe_true" class="styled"><span class="check-symbol"></span>
					</label>
					<div>
						<label for="udesfe_true" class="mb-0 radio-label d-inline-block">{{ trans('messages.site.type.landing_page') }}</label>
						<p class="text-muted mb-2 small fst-italic">
							{{ trans('messages.site.type.landing_page.wording') }}
						</p>
					</div>
				</div>
				<div data-control="option-container" class="d-flex mt-2 pe-none" style="opacity: 0.5;">
					<label class="me-3">
						<input type="radio" name="site[type]" value="0" id="udesfe_false" class="styled"><span class="check-symbol"></span>
					</label>
					<div>
						<label for="udesfe_false" class="mb-0 radio-label">{{ trans('messages.site.type.website') }} (coming soon)</label>
						<p class="text-muted mb-2 small fst-italic">
							{{ trans('messages.site.type.website.wording') }}
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>