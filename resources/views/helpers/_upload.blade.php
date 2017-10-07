<div class="form-group control-text">
    <div class="sub_section">
        <h2 class="text-semibold text-teal-800">{{ trans('messages.profile_photo') }}</h2>
        <div class="media profile-image">
            <div class="media-left zone-upload" id="{{$dragId}}">
                <a href="#" class="upload-media-container">
                    <img preview-for="image" empty-src="{{ URL::asset('assets/images/placeholder.jpg') }}" src="{{ $src }}" class="img-circle" alt="">
                </a>
                <input type="file" name="image" id="{{$preview}}" accept="image/*" class="file-styled previewable hide">
                <input type="hidden" name="_remove_image" value='' />
            </div>
            <div class="media-body text-center">
                <h5 class="media-heading text-semibold">{{ trans('messages.upload_your_photo') }}</h5>
                {{ trans('messages.photo_at_least', ["size" => "300px x 300px"]) }}
                <br /><br />
                <a href="#upload" onclick="$('input[name=image]').trigger('click')" class="btn btn-xs bg-teal mr-10"><i class="icon-upload4"></i> {{ trans('messages.upload') }}</a>
                <a href="#remove" class="btn btn-xs bg-grey-800 remove-profile-image"><i class="icon-trash"></i> {{ trans('messages.remove') }}</a>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        var element = document.getElementById("{{$dragId}}");
        var image = document.getElementById("{{$preview}}");
        element.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            element.style="background: #c7dade"

        });

        element.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            element.style="background: white"

        });
        element.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            element.style="background: white";
            var imageType = /image.*/;
            if (e.dataTransfer.files[0].type.match(imageType)) {
                image.files = e.dataTransfer.files
            }

        });
    })
</script>