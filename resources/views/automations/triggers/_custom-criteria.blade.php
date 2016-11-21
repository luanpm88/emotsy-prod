                            <div class="col-md-8">
                                <hr>
                                <h5 class="text-semibold">{{ trans('messages.Trigger_the_automation_for_the_following_recur') }}</h5>
                                <div class="addable-multiple-form">
                                    <div class="addable-multiple-container">
                                        
                                    </div>
                                        
                                    <br />
                                    <a sample-url="{{ action('AutomationController@criteriaForm', $automation->uid) }}" href="#add_condition" class="btn btn-info bg-info-800 add-form">
                                        <i class="icon-plus2"></i> {{ trans('messages.Add_criteria') }}
                                    </a>
                                </div>
							</div>