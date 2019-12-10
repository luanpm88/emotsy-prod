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

<br>
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
            tree.getSelected().setOptions($.extend(tree.getOptions(), response.options));
            tree.getSelected().setOptions($.extend(tree.getOptions(), {init: true}));
            
            // notify
            notify('success', '{{ trans('messages.notify.success') }}', response.message);
            
            // reload sidebar
            sidebar.load();
        });        
    });
</script>