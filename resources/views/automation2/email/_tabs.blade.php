<ul class="nav nav-tabs mb-4 email_tabs">
    <li class="nav-item">
        <a class="nav-link setup" href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
            'uid' => $automation->uid,
            'email_uid' => $email->uid,
        ]) }}')">
            <i class="lnr lnr-cog mr-2"></i>
            {{ trans('messages.automation.email.setup') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link template {{ (!isset($email->id)) ? 'disabled' : '' }}" href="javascript:;"
            @if (isset($email->id))                    
                onclick="popup.load('{{ action('Automation2Controller@emailTemplate', [
                    'uid' => $automation->uid,
                    'email_uid' => $email->uid,
                ]) }}')"
            @endif
        >
            <i class="lnr lnr-layers mr-2"></i>
            {{ trans('messages.automation.email.content') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link confirm {{ (!isset($email->id)) ? 'disabled' : '' }}" href="javascript:;"
            @if (isset($email->id))                    
                onclick="popup.load('{{ action('Automation2Controller@emailConfirm', [
                    'uid' => $automation->uid,
                    'email_uid' => $email->uid,
                ]) }}')"
            @endif
        >
            <i class="lnr lnr-cog mr-2"></i>
            {{ trans('messages.automation.email.confirm') }}
        </a>
    </li>
</ul>
    
<script>
    @if (isset($tab))
        $('.email_tabs .nav-link.{{ $tab }}').addClass('active');
    @endif
    @if (isset($sub))
        $('.email_tabs .dropdown-item.{{ $sub }}').addClass('active');
    @endif
</script>