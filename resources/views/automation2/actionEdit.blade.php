@include('automation2._info')

<ul class="nav nav-tabs mt-3 mb-4">
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

<form id="action-edit" action="{{ action("Automation2Controller@actionEdit", ['uid' => $automation->uid, 'type' => $type]) }}" method="POST" class="form-validate-jqueryz">
    {{ csrf_field() }}
    
    <input type="hidden" name="type" value="{{ $type }}" />
    
    @if(View::exists('automation2.action.' . $type))
        @include('automation2.action.' . $type)
    @endif
    
    <div class="trigger-action mt-4">    
        <button class="btn btn-secondary action-save-change mr-1"
            data-url="{{ action('Automation2Controller@triggerSelect', ['uid' => $automation->uid, 'type' => $type]) }}"
        >
                {{ trans('messages.automation.action.save_change') }}
        </button>
    </div>
</form>

<h6 class="mb-2 mt-5 text-danger">
    {{ trans('messages.automation.dangerous_zone') }}
</h6>
<p class="">
    {{ trans('messages.automation.action.delete.confirm') }}        
</p>
<div class="mt-3">
    <button class="btn btn-danger">
        <i class='lnr lnr-trash mr-2'></i> Delete This Action</button>
</div>
    
<script>
    $('#action-edit').submit(function(e) {
        e.preventDefault();
        
        var form = $(this);
        var data = form.serialize();
        var url = form.attr('action');
        
        sidebar.loading();
        
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
        }).always(function(response) {
            // set node title
            tree.getSelected().setTitle(response.title);
            // merge options with reponse options
            tree.getSelected().setOptions($.extend(tree.getSelected().getOptions(), response.options));
            tree.getSelected().setOptions($.extend(tree.getSelected().getOptions(), {init: true}));
            
            // notify
            notify('success', '{{ trans('messages.notify.success') }}', response.message);
            
            // save tree
            saveData();
            
            // reload sidebar
            sidebar.load();
        });        
    });
</script>