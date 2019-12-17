@include('automation2._info')

@include('automation2._tabs', ['tab' => 'settings'])
    
<h5 class="mb-20 mt-3"h5>
    {{ trans('messages.automation.action.send-an-email') }}
</h5>
<p class="mb-10">
    {{ trans('messages.automation.action.send-an-email.intro') }}
</p>

<form action="{{ action('Automation2Controller@emailSetup', $automation->uid) }}" method="POST" class="form-validate-jqueryz">
    {{ csrf_field() }}
    
    <ul class="key-value-list mt-4 list-small">
        <li class="d-flex">
            <div class="values">
                <label>
                    {{ trans('messages.automation.email.recipients_count', ['count' => $automation->mailList->subscribersCount()]) }}
                </label>
                <div class="value">
                    {{ $automation->mailList->name }}
                </div>
            </div>
        </li>
        <li class="d-flex">
            <div class="values">
                <label>
                    {{ trans('messages.automation.email.subject') }}
                </label>
                <div class="value">
                    {{ $email->subject }}
                </div>
            </div>
        </li>
        <li class="d-flex">
            <div class="values">
                <label>
                    {{ trans('messages.automation.email.from') }}
                </label>
                <div class="value">
                    {{ $email->from }}
                </div>
            </div>
        </li>
        <li class="d-flex">
            <div class="values">
                <label>
                    {{ trans('messages.reply_to') }}
                </label>
                <div class="value">
                    @if($email->reply_to)
                        {{ $email->reply_to }}
                    @else
                        <span class="text-warning small">
                            <i class="material-icons-outlined">warning</i>
                            {{ trans('messages.email.no_reply_to') }}
                        </span>
                    @endif
                </div>
            </div>
        </li>
        <li class="d-flex">
            <div class="values">
                <label>
                    {{ trans('messages.automation.email.template') }}
                </label>
                <div class="value">
                    @if($email->content)
                        {{ trans('messages.automation.email.template_selected_click_edit', [
                            'link' => '',
                        ]) }}
                    @else
                        <span class="text-danger small">
                            <i class="material-icons-outlined">error_outline</i>
                            {{ trans('messages.email.no_template') }}
                        </span>
                    @endif
                </div>
            </div>
        </li>
        <li class="d-flex">
            <div class="values">
                <label>
                    {{ trans('messages.automation.email.tracking') }}
                </label>
                <div class="value">
                    @if ($email->track_open)
                        {{ trans('messages.automation.email.opens') }}@if ($email->track_click),@endif
                    @endif
                    @if ($email->track_click)
                        {{ trans('messages.automation.email.clicks') }}
                    @endif
                </div>
            </div>
        </li>
    </ul>    
    
    <div class="trigger-action mt-4">    
        <span class="btn btn-secondary email-settings-change mr-1"
        >
            {{ trans('messages.automation.email.settings') }}
        </span>
    </div>
    
<form>

<h6 class="mb-2 mt-5 text-danger">
    {{ trans('messages.automation.dangerous_zone') }}
</h6>
<p class="">
    {{ trans('messages.automation.action.delete.confirm') }}        
</p>
<div class="mt-3">
    <a href="javascript:;" data-confirm="{{ trans('messages.automation.email.delete.confirm') }}" class="btn btn-danger action-delete">
        <i class='lnr lnr-trash mr-2'></i> {{ trans('messages.automation.delete_this_action') }}
    </a>
</div>

<script>
    // Click on exist action
    $('.email-settings-change').click(function(e) {
        e.preventDefault();
        
        var url = '{{ action('Automation2Controller@emailSetup', ['uid' => $automation->uid, 'email_uid' => $email->uid]) }}';
        
        popup = new Popup(url);
        
        popup.load();
    });
    
    $('.action-delete').click(function(e) {
        e.preventDefault();
        
        var confirm = $(this).attr('data-confirm');
        var dialog = new Dialog('confirm', null, confirm, function(dialog) {
            // remove current node
            tree.getSelected().remove();        
            
            // notify
            notify('success', '{{ trans('messages.notify.success') }}', '{{ trans('messages.automation.email.deteled') }}');
            
            // load default sidebar
            sidebar.load('{{ action('Automation2Controller@settings', $automation->uid) }}');
            
            // save tree
            saveData();
        });
    });
</script>