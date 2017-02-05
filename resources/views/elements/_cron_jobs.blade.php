<h3 class="text-teal-800"><i class="icon-puzzle2"></i> {{ trans('messages.setting_up_cron_jobs') }}</h3>

@include('helpers.form_control', [
    'type' => 'radio',
    'name' => 'queue_driver',
    'class' => '',
    'label' => 'Select system background jobs proccessing method',
    'value' => $queue_driver,
    'options' => \Acelle\Library\Tool::availableSystemBackgroundMethodSelectOptions(),
    'rules' => [['queue_driver' => 'required']]
])

<div class="database-config-box">

    @include('helpers.form_control', [
        'type' => 'radio',
        'name' => 'php_bin_path',
        'class' => '',
        'label' => 'Find PHP BINARY PATH for background jobs',
        'value' => $php_bin_path,
        'options' => \Acelle\Library\Tool::phpPathsSelectOptions($php_paths),
        'rules' => [['php_bin_path' => 'required']]
    ])
    
    <div class="php_bin_path_value_box">
        @include('helpers.form_control', [
            'type' => 'text',
            'name' => 'php_bin_path_value',
            'class' => '',
            'label' => 'Enter your system crontab here',
            'value' => $php_bin_path_value,
            'rules' => [['php_bin_path_value' => 'required']]
        ])    
    </div>
    
    @if ($errors->has('php_bin_path_invalid') && \Acelle\Library\Tool::checkPHPBinPath($php_bin_path_value) != '')
        <div class="alert alert-danger">
            {!! \Acelle\Library\Tool::checkPHPBinPath($php_bin_path_value) !!}
        </div>
    @endif
    
    <hr>

    <label class="text-bold">{!! trans('messages.cron_jobs_guide') !!} </label>
    <pre style="font-size: 16px;background:#f5f5f5">* * * * * <span class="current_path_value">{!! $php_bin_path_value !!}</span> -q {{ base_path() }}/artisan schedule:run 2&gt;&amp;1    </pre>
    
</div>
    
<script>
    $(document).ready(function() {
        // pickadate mask
        $(document).on('change', 'input[name="php_bin_path"]', function() {      
            var value = $(this).val();
            var old = $('.current_path_value').attr('old');
            
            if(value !== 'manual') {
                $('.current_path_value').html(value);
                $('input[name="php_bin_path_value"]').val(value);
            }
            
            if(value === 'manual') {
                $('.php_bin_path_value_box').show();
                $('input[name="php_bin_path_value"]').trigger('change');
            } else {
                $('.php_bin_path_value_box').hide();
            }
        });
        $('input[name="php_bin_path"]:checked').trigger('change');
        
        // pickadate mask
        $(document).on('keyup change', 'input[name="php_bin_path_value"]', function() {      
            var value = $(this).val();
            
            if(value !== '') {
                $('.current_path_value').html(value);
            } else {
                $('.current_path_value').html('{PHP_BIN_PATH}');
            }
        });
        $('input[name="php_bin_path_value"]').trigger('change');
        
        // pickadate mask
        $(document).on('change', 'input[name="queue_driver"]', function() {      
            var value = $(this).val();
            
            if(value === 'database') {
                $('.database-config-box').show();
            } else {
                $('.database-config-box').hide();
            }
        });
        $('input[name="queue_driver"]:checked').trigger('change');
    });
</script>
