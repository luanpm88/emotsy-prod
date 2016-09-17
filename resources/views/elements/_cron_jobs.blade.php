<h3 class="text-teal-800"><i class="icon-puzzle2"></i> {{ trans('messages.setting_up_cron_jobs') }}</h3>
    
<div class="">
    {!! trans('messages.cron_jobs_guide') !!}    
</div>
<br/>

@if ()
    
@endif

<pre style="font-size: 16px;background:#f5f5f5"># {{ trans('messages.cron_jobs_comment') }}
* * * * * {{ (isset(explode(" ", exec("whereis php"))[1]) ? explode(" ", exec("whereis php"))[1] : "{PHP_BIN_PATH}") }} -q {{ base_path() }}/artisan handler:run 2&gt;&amp;1
* * * * * {{ (isset(explode(" ", exec("whereis php"))[1]) ? explode(" ", exec("whereis php"))[1] : "{PHP_BIN_PATH}") }} -q {{ base_path() }}/artisan campaign:run 2&gt;&amp;1
</pre>