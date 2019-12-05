<ul class="nav nav-tabs mt-5">
    <li class="nav-item">
        <a class="nav-link active" href="#">
            <i class="lnr lnr-exit-up mr-2"></i>
            Trigger
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
                        
<h5 class="mb-3 mt-4">
    {{ trans('messages.automation.trigger.' . $type) }}
</h5>
<p class="mb-10">
    {{ trans('messages.automation.trigger.' . $type . '.intro') }}
</p>
    
<form id="trigger-select" action="{{ action("Automation2Controller@triggerEdit", ['uid' => $automation->uid, 'type' => $type]) }}" method="POST" class="form-validate-jqueryz">
    {{ csrf_field() }}
    
    <input type="hidden" name="type" value="{{ $type }}" />
    
    @if(View::exists('automation2.trigger.' . $type))
        @include('automation2.trigger.' . $type)
    @endif
    
    <div class="trigger-action mt-4">    
        <button class="btn btn-secondary select-trigger-confirm mr-1"
            data-url="{{ action('Automation2Controller@triggerSelect', ['uid' => $automation->uid, 'type' => $type]) }}"
        >
                {{ trans('messages.automation.trigger.save_change') }}
        </button>
        
        <a href="javascript:;" class="btn btn-outline-secondary add-trigger-but">
            Change Trigger
        </a>
    </div>
</form>