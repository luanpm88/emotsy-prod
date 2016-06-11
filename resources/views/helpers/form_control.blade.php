                                <?php
                                    $label = isset($label) ? $label : (Lang::has('messages.' . $name) ? trans('messages.' . $name) : "");
                                    $classes = (isset($rules) && isset($rules[$name])) ? " " . str_replace("|", " ", $rules[$name]) : "";
                                    $required = (isset($rules) && isset($rules[$name]) && in_array("required", explode("|", $rules[$name]))) ? true : "";
                                    $var_name = str_replace("][", ".", $name);
                                    $var_name = str_replace("[", ".", $var_name);
                                    $var_name = str_replace("]", "", $var_name);
                                ?>
                                
                                <div class="form-group{{ $errors->has($var_name) ? ' has-error' : '' }}">

                                    <label>
                                        {{ $label }}
                                        @if (!empty($label) && $required)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    
									@include('helpers._' . $type)
                                    
                                    @if (isset($help_class) && Lang::has('messages.' . $help_class . '.' . $name . '.help'))
                                        <div class="help alert alert-info">
                                            {{ trans('messages.' . $help_class . '.' . $name . '.help') }}
                                        </div>
                                    @endif
                                    
                                    @if ($errors->has($var_name))
                                        <span class="help-block">
                                            <strong>{{ $errors->first($var_name) }}</strong>
                                        </span>
                                    @endif
								</div>