<h3 class="text-teal-800"><i class="icon-puzzle2"></i> {{ trans('messages.setting_up_cron_jobs') }}</h3>
    
@include('helpers.form_control', [
    'type' => 'radio',
    'name' => 'php_bin_path',
    'class' => '',
    'label' => 'Select your PHP BINARY PATH',
    'value' => $current_path,
    'options' => \Acelle\Library\Tool::phpPathsSelectOptions($php_paths),
    'rules' => $rules
])

<div class="php_bin_path_value_box">
    @include('helpers.form_control', [
        'type' => 'text',
        'name' => 'php_bin_path_value',
        'class' => '',
        'label' => 'Enter your system crontab here',
        'value' => '',
        'rules' => $rules
    ])
    <button style="margin-top: -10px; margin-bottom: 0px;" class="btn btn-primary bg-info-800">
        {!! trans('messages.check_php_bin_path') !!}</i>
    </button>
</div>
    
<hr>

<label class="text-bold">{!! trans('messages.cron_jobs_guide') !!} </label>
<pre style="font-size: 16px;background:#f5f5f5">* * * * * <span class="current_path_value">{!! $current_path_value !!}</span> -q {{ base_path() }}/artisan schedule:run 2&gt;&amp;1
</pre>
    
<script>
    $(document).ready(function() {
        // pickadate mask
        $(document).on('change', 'input[name="php_bin_path"]', function() {      
            var value = $(this).val();
            
            if(value !== '') {
                $('.current_path_value').html(value);
            }
            
            if(value === 'manual') {
                $('.php_bin_path_value_box').show();
            } else {
                $('.php_bin_path_value_box').hide();
            }
        });
        $('input[name="php_bin_path"]:checked').trigger('change');
    });
</script>
