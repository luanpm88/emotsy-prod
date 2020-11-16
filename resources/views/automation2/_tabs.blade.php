<ul class="nav nav-tabs mt-3 mb-4">
<li class="nav-item">
    <a class="nav-link settings" href="javascript:;" onclick="sidebar.load('{{ action('Automation2Controller@settings', $automation->uid) }}')">
        <i class="material-icons-outlined mr-2">menu</i>
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
        <a class="dropdown-item contacts_in_action d-flex align-items-center" href="#" onclick="sidebar.load('{{ action('Automation2Controller@contacts', [
            'uid' => $automation->uid,
            'type' => 'in_action',
        ]) }}')">
            <i class="material-icons-outlined mr-2">more_horiz</i>
            <span>{{ trans('messages.automation.contacts_in_action') }}
                ({{ $automation->subscribers()->count() }})
            </span>
        </a>
        <a class="dropdown-item contacts_done d-flex align-items-center" href="#" onclick="sidebar.load('{{ action('Automation2Controller@contacts', [
            'uid' => $automation->uid,
            'type' => 'done',
        ]) }}')">
            <i class="material-icons-outlined mr-2">done_all</i>
            <span>{{ trans('messages.automation.contacts_done') }}
                ({{ $automation->subscribers()->count() }})
            </span>
        </a>
        <a class="dropdown-item contacts_pending d-flex align-items-center" href="#" onclick="sidebar.load('{{ action('Automation2Controller@contacts', [
            'uid' => $automation->uid,
            'type' => 'pending',
        ]) }}')">
            <i class="material-icons-outlined mr-2">pause</i>
            <span>{{ trans('messages.automation.contacts_skip_pending') }}
                ({{ $automation->subscribers()->count() }})
            </span>
        </a>
        <a class="dropdown-item contacts_error d-flex align-items-center" href="#" onclick="sidebar.load('{{ action('Automation2Controller@contacts', [
            'uid' => $automation->uid,
            'type' => 'error',
        ]) }}')">
            <i class="material-icons-outlined d-flex align-items-center mr-2">priority_high</i>
            <span>{{ trans('messages.automation.contacts_error') }}
                ({{ $automation->subscribers()->count() }})
            </span>
        </a>
        <div class="dropdown-divider"></div>
        @foreach ($automation->getInsight() as $key => $element)
            @php
                $action = $automation->getElement($key);
            @endphp

            <a class="xtooltip dropdown-item contacts_action_{{ $key }} d-flex align-items-center" href="#" onclick="sidebar.load('{{ action('Automation2Controller@contacts', [
                'uid' => $automation->uid,
                'action_id' => $key
            ]) }}')"
                title="{{ $action->getName() }}"
            >
                {!! $action->getIconWithoutBg() !!}
                <span class="ml-2 mr-2"
                    style="
                        display: inline-block;
                        max-width: 280px;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        overflow: hidden;
                    "
                >{{ $action->getName() }}</span>
                <count>({{ format_number($element['count']) }})</count>
            </a>                
        @endforeach
    </div>
</li>
</ul>
    
<script>
    @if (isset($tab))
        $('.nav-link.{{ $tab }}').addClass('active');
    @endif
    @if (isset(request()->type))
        $('.dropdown-item.contacts_{{ request()->type }}').addClass('active');
    @endif
    @if (isset($sub))
        $('.dropdown-item.{{ $sub }}').addClass('active');
    @endif
</script>