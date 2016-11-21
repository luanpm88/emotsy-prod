                            <div class="col-md-6">
                                <hr>
                                <h5 class="text-semibold">{{ trans('messages.Trigger_the_automation_for_the_following_recur') }}</h5>
                                <div class="addable-multiple-form">
                                    <div class="addable-multiple-container">
                                        <div class="row">
                                            <div class="col-md-6">
                                                @include('helpers.form_control', [
                                                    'type' => 'select',
                                                    'name' => 'type',
                                                    'class' => '',
                                                    'label' => '',
                                                    'value' => '',
                                                    'options' => [
                                                        ['value' => 'specific-date', 'text' => trans('messages.Subscriber_added_date')],
                                                        ['value' => 'annual-recurring', 'text' => trans('messages.Annual_recurring')],
                                                    ],
                                                    'rules' => []
                                                ])
                                            </div>
                                            <div class="col-md-3">
                                                @include('helpers.form_control', [
                                                    'type' => 'select',
                                                    'name' => '',
                                                    'label' => '',
                                                    'value' => '',
                                                    'options' => Acelle\Model\Segment::operators()
                                                ])
                                            </div>
                                            <div class="col-md-3">
                                                @include('helpers.form_control', [
                                                    'type' => 'text',
                                                    'name' => '',
                                                    'label' => '',
                                                    'value' => '',
                                                ])
                                            </div>
                                        </div>
                                    </div>
                                        
                                    <br />
                                    <a sample-url="{{ action('AutomationController@criteriaForm', $automation->uid) }}" href="#add_condition" class="btn btn-info bg-teal-800 add-form">
                                        {{ trans('messages.Add_criteria') }}
                                    </a>
                                </div>
							</div>