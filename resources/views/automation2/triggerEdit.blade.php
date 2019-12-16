@include('automation2._info')

@include('automation2._tabs', ['tab' => 'settings'])
                        
<h5 class="mb-3 mt-4">
    {{ trans('messages.automation.trigger.' . $key) }}
</h5>
<p class="mb-10">
    {{ trans('messages.automation.trigger.' . $key . '.intro') }}
</p>
    
<form id="trigger-select" action="{{ action("Automation2Controller@triggerEdit", ['uid' => $automation->uid, 'key' => $key]) }}" method="POST" class="form-validate-jqueryz">
    {{ csrf_field() }}
    
    <input type="hidden" name="options[key]" value="{{ $key }}" />
    
    @if(View::exists('automation2.trigger.' . $key))
        @include('automation2.trigger.' . $key)
    @endif
    
    <div class="trigger-action mt-2">    
        <button class="btn btn-secondary trigger-save-change mr-1">
                {{ trans('messages.automation.trigger.save_change') }}
        </button>
        
        <a href="javascript:;" class="btn btn-outline-secondary add-trigger-but">
            Change Trigger
        </a>
    </div>
</form>
    
<script>
    $('#trigger-select').submit(function(e) {
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
            tree.setTitle(response.title);
            // merge options with reponse options
            tree.setOptions(response.options);
            tree.setOptions($.extend(tree.getOptions(), {init: true}));
            
            // notify
            notify('success', '{{ trans('messages.notify.success') }}', response.message);
            
            // save tree
            saveData();
            
            // reload sidebar
            sidebar.load();
        });        
    });
</script>