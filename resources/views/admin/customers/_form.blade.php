<div class="row">
    <div class="col-md-12">
        <div class="sub_section">
            <h2 class="text-semibold text-primary">{{ trans('messages.profile_photo') }}</h2>
            <div class="media profile-image">
                <div class="media-left">
                    <a href="#" class="upload-media-container">
                        <img preview-for="image" empty-src="{{ AppUrl::asset('images/placeholder.jpg') }}" src="{{ $customer->user->getProfileImageUrl() }}" class="rounded-circle" alt="">
                    </a>
                    <input type="file" name="image" class="file-styled previewable hide">
                    <input type="hidden" name="_remove_image" value='' />
                </div>
                <div class="media-body text-center">
                    <h5 class="media-heading text-semibold">{{ trans('messages.upload_photo') }}</h5>
                    {{ trans('messages.photo_at_least', ["size" => "300px x 300px"]) }}
                    <br /><br />
                    <a href="#upload" onclick="$('input[name=image]').trigger('click')" class="btn btn-primary me-1"><span class="material-symbols-rounded">file_download</span> {{ trans('messages.upload') }}</a>
                    <a href="#remove" class="btn btn-secondary remove-profile-image"><span class="material-symbols-rounded">delete_outline</span> {{ trans('messages.remove') }}</a>
                </div>
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