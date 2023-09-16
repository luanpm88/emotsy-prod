<div class="row">
    <div class="col-md-6">
        <div class="sub_section">
            <h3 class="text-semibold text-primary mb-4">{{ trans('messages.account.information') }}</h3>

            <div class="form-group">
                <label for="">{{ trans('messages.account.name') }}</label>
                <input type="text" name="name" value="{{ $customer->name }}" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" />
            </div>

            @if (config('custom.japan'))
                <input type="hidden" name="timezone" value="Asia/Tokyo" />
            @else
                @include('helpers.form_control', [
                    'type' => 'select',
                    'name' => 'timezone',
                    'value' => $customer->timezone ?? config('app.timezone'),
                    'options' => Tool::getTimezoneSelectOptions(),
                    'include_blank' => trans('messages.choose'),
                    'rules' => ['name' => 'required'],
                ])
            @endif
                

            @if (config('custom.japan'))
                <input type="hidden" name="language_id" value="{{ Acelle\Model\Language::getJapan()->id }}" />
            @else
                @include('helpers.form_control', [
                    'type' => 'select',
                    'name' => 'language_id',
                    'label' => trans('messages.language'),
                    'value' => $customer->language_id ?? \Acelle\Model\Language::getDefaultLanguage()->id,
                    'options' => Acelle\Model\Language::getSelectOptions(),
                    'include_blank' => trans('messages.choose'),
                    'rules' => ['name' => 'required'],
                ])
            @endif

        </div>
    </div>
</div>
<hr>
<div class="text-left">
    <button class="btn btn-secondary"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
</div>

<script>
	$(function() {
		// Preview upload image
		$("input.previewable").on('change', function() {
			var img = $("img[preview-for='" + $(this).attr("name") + "']");
			previewImageBrowse(this, img);
		});
		$(".remove-profile-image").click(function() {
			var img = $(this).parents(".profile-image").find("img");
			var imput = $(this).parents(".profile-image").find("input[name='_remove_image']");
			img.attr("src", img.attr("empty-src"));
			imput.val("true");
		});
	});
</script>