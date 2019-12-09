<ul class="nav nav-tabs mt-5">
    <li class="nav-item">
        <a class="nav-link active" href="#">
            <i class="lnr lnr-exit-up mr-2"></i>
            Action
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="lnr lnr-layers mr-2"></i>
            Information
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="lnr lnr-cog mr-2"></i>
            Settings
        </a>
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
                Attachment
            </label>
            <div class="value">
                (2) files
            </div>
        </li>
    </ul>    
    
    <div class="trigger-action mt-4">    
        <span class="btn btn-secondary email-settings-change mr-1"
        >
                {{ trans('messages.automation.email.settings') }}
        </span>
        
        <a href="javascript:;" class="btn btn-outline-secondary change-action-but">
            Change Action
        </a>
    </div>
    
<form>

<script>
    // Click on exist action
    $(document).on('click', '.email-settings-change', function(e) {
        e.preventDefault();
        
        var url = '{{ action('Automation2Controller@emailSetup', ['uid' => $automation->uid, 'email_uid' => $email->uid]) }}';
        
        popup = new Popup(url);
        
        popup.load();
    });
</script>