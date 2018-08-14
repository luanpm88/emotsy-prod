<div class="sub-section">
    <h3>{{ trans('messages.advanced_settings') }}</h3>
    <div class="row">
        <?php $count = 0; ?>
        @foreach ($settings as $name => $setting)
            @if (isset($setting['cat']) && $setting['cat'] == 'advanced')
                <div class="col-md-6">
                    @if ($setting['type'] == 'checkbox')
                        <div class="form-group checkbox-right-switch">
                    @endif
                        @include('helpers.form_control', [
                            'type' => $setting['type'],
                            'class' => (isset($setting['class']) ? $setting['class'] : "" ),
                            'name' => $name,
                            'value' => $setting['value'],
                            'label' => trans('messages.' . $name),
                            'help_class' => 'setting',
                            'options' => (isset($setting['options']) ? $setting['options'] : "" ),
                            'rules' => Acelle\Model\Setting::rules(),
                        ])
                    @if ($setting['type'] == 'checkbox')
                        </div>
                    @endif
                </div>
                @if ($count%2 == 1)
    </div><div class="row">
                @endif
                <?php ++$count; ?>
            @endif
        @endforeach
    </div>
</div>