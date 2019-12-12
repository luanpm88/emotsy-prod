@include('automation2._info')

<ul class="nav nav-tabs mt-3">
    <li class="nav-item">
        <a class="nav-link active" href="#">
            <i class="lnr lnr-cog mr-2"></i>
            {{ trans('messages.automation.settings') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="lnr lnr-chart-bars mr-2"></i>
            {{ trans('messages.automation.insight') }}
        </a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="lnr lnr-menu mr-2"></i> {{ trans('messages.automation.statistics') }}
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Separated link</a>
        </div>
    </li>
</ul>
    
<h5 class="mb-20 mt-3"h5>
    {{ trans('messages.automation.action.send-an-email') }}
</h5>
<p class="mb-10">
    {{ trans('messages.automation.action.send-an-email.intro') }}
</p>

<form action="{{ action('Automation2Controller@emailSetup', $automation->uid) }}" method="POST" class="form-validate-jqueryz">
    {{ csrf_field() }}
    
    <ul class="key-value-list list-small">
        <li>
            <label>
                Subject
            </label>
            <div class="value">
                {{ $email->subject }}
            </div>
        </li>
        <li>
            <label>
                From
            </label>
            <div class="value">
                {{ $email->from }}
            </div>
        </li>
        <li>
            <label>
                {{ trans('messages.automation.email.attachment') }}
            </label>
            <div class="value">
                {{ trans('messages.automation.email.attachment_count', ['count' => $email->attachments()->count()]) }}
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