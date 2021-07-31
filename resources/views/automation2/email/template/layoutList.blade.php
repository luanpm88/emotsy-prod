@if ($templates->count() >0)
    <div class="template-list-items mt-3 clearfix">
        @foreach ($templates as $key => $template)
            <a href="javascript:;" class="select-template-layout template-list-item template-choose" data-template="{{ $template->uid }}">
                <div class="panel-template-style">
                    <div class="panel-template-placeholder">
                        <img href="javascript:;" src="{{ $template->getThumbUrl() }}?v={{ rand(0,10) }}" />
                    </div>
                    <label class="mb-20 text-center d-block mt-1">{{ $template->name }}</label>
                </div>
            </a>
        @endforeach
    </div>
        
    
@else
    <div class="empty-list">
        <i class="material-icons-outlined">featured_play_list</i>
        <span class="line-1">
            {{ trans('messages.automation.email.empty_template_list') }}
        </span>
    </div>
@endif

<script>
    var builderSelectPopup;

    $('.template-choose').click(function(e) {
        e.preventDefault();
        
        var url = '{{ action('Automation2Controller@templateLayout', [
            'uid' => $automation->uid,
            'email_uid' => $email->uid,
        ]) }}';
        var template_uid = $(this).attr('data-template');
        
        // loading popup
        popup.loading();
        
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                template_uid: template_uid
            }
        }).always(function(response) {
            popup.load('{{ action('Automation2Controller@emailTemplate', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}');

            builderSelectPopup = new Popup(null, undefined, {onclose: function() {
            
            }});
            builderSelectPopup.load('{{ action('Automation2Controller@templateBuilderSelect', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}');

            // notify
            notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});
        });
    });
</script>