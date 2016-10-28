<h3 class="text-teal-800"><i class="icon-puzzle2"></i> {{ trans('messages.setting_up_cron_jobs') }}</h3>
    
<div class="">
    {!! trans('messages.cron_jobs_guide') !!}    
</div>
<br/>

@if (!isset(explode(" ", exec("whereis php"))[1]))
    <div class="alert alert-danger">
        Cannot find PHP_BIN_PATH in your server. Please find it and replace all {PHP_BIN_PATH} text below with that one.<br /> Ex: /usr/bin/php7.0, /usr/bin/php, /usr/lib/php.
    </div>
@endif

<pre style="font-size: 16px;background:#f5f5f5"># {{ trans('messages.cron_jobs_comment') }}
* * * * * {!! (isset(explode(" ", exec("whereis php"))[1]) ? explode(" ", exec("whereis php"))[1] : "<span class='text-danger'>{PHP_BIN_PATH}</span>") !!} -q {{ base_path() }}/artisan handler:run 2&gt;&amp;1
* * * * * {!! (isset(explode(" ", exec("whereis php"))[1]) ? explode(" ", exec("whereis php"))[1] : "<span class='text-danger'>{PHP_BIN_PATH}</span>") !!} -q {{ base_path() }}/artisan campaign:run 2&gt;&amp;1
# {{ trans('messages.cron_jobs_queue') }}
* * * * * {!! (isset(explode(" ", exec("whereis php"))[1]) ? explode(" ", exec("whereis php"))[1] : "<span class='text-danger'>{PHP_BIN_PATH}</span>") !!} -q {{ base_path() }}/artisan queue:work --tries=1 >> {{ storage_path() }}/logs/queue.log 2&gt;&amp;1
</pre>