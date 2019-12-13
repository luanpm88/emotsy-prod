<ul class="nav nav-tabs mt-3 mb-4">
<li class="nav-item">
    <a class="nav-link settings" href="javascript:;" onclick="sidebar.load('{{ action('Automation2Controller@settings', $automation->uid) }}')">
        <i class="material-icons-outlined mr-2">settings_input_component</i>
        {{ trans('messages.automation.settings') }}
    </a>
</li>
<li class="nav-item">
    <a class="nav-link insight" href="javascript:;" onclick="sidebar.load('{{ action('Automation2Controller@insight', $automation->uid) }}')">
        <i class="material-icons mr-2">bubble_chart</i>
        {{ trans('messages.automation.insight') }}
    </a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link statistics dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <i class="material-icons mr-2">multiline_chart</i> {{ trans('messages.automation.statistics') }}
    </a>
    <div class="dropdown-menu dropdown-menu-right with-icon">
        <a class="dropdown-item timeline" href="#" onclick="sidebar.load('{{ action('Automation2Controller@timeline', $automation->uid) }}')">
            <i class="material-icons mr-2">timeline</i> {{ trans('messages.automation.timeline') }}
        </a>
        <a class="dropdown-item all_contacts" href="#" onclick="sidebar.load('{{ action('Automation2Controller@contacts', $automation->uid) }}')">
            <i class="material-icons-outlined mr-2">people</i> {{ trans('messages.automation.all_contacts') }} (4,600)
        </a>
        <a class="dropdown-item" href="#" onclick="sidebar.load('{{ action('Automation2Controller@contacts', $automation->uid) }}')">
            Contacts subscribed to list (200)
        </a>
        <a class="dropdown-item" href="#" onclick="sidebar.load('{{ action('Automation2Controller@contacts', $automation->uid) }}')">
            Contacts receiving welcome email (200)
        </a>
        <a class="dropdown-item" href="#" onclick="sidebar.load('{{ action('Automation2Controller@contacts', $automation->uid) }}')">
            Contacts click VNExpress.ner link (35)
        </a>
    </div>
</li>
</ul>
    
<script>
    @if (isset($tab))
        $('.nav-link.{{ $tab }}').addClass('active');
    @endif
    @if (isset($sub))
        $('.dropdown-item.{{ $sub }}').addClass('active');
    @endif
</script>