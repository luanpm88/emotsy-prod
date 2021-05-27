<ul class="nav nav-tabs mb-4 timeline-tab">
    <li class="nav-item dropdown">
        <a href="javascript:;" class="nav-link {{ controllerAction() == 'Automation2Controller@contacts' ? 'active' : '' }}" onclick="timelinePopup.load('{{ action('Automation2Controller@contacts', [
            'uid' => $automation->uid,
        ]) }}')">
            {{ trans('messages.automation.audience') }}
        </a>
    </li>
    <li class="nav-item dropdown">
        <a href="javascript:;" class="nav-link {{ controllerAction() == 'Automation2Controller@timeline' ? 'active' : '' }}" onclick="timelinePopup.load('{{ action('Automation2Controller@timeline', $automation->uid) }}')">
            {{ trans('messages.automation.timeline') }}
        </a>
    </li>
</ul>
    
<script>
    @if (isset($tab))
        $('.timeline-tab .nav-link.{{ $tab }}').addClass('active');
    @endif
    @if (isset(request()->type))
        $('.timeline-tab .dropdown-item.contacts_{{ request()->type }}').addClass('active');
    @endif
    @if (isset($actionId))
        $('.timeline-tab .dropdown-item.contacts_action_{{ $actionId }}').addClass('active');
    @endif
</script>