
												<input type="hidden" name="{{ $name }}" value="{{ $options[0] }}" />
												<input{{ $value == 1 ? " checked" : "" }} type="checkbox" id="{{ $name }}" name="{{ $name }}" value="{{ $options[1] }}" class="switch" data-on-text="On" data-off-text="Off" data-on-color="success" data-off-color="default">
												<label>
													@if (!empty($label))
														{!! $label !!}
													@endif
													
													@if (isset($help_class) && Lang::has('messages.' . $help_class . '.' . $name . '.help'))
														<span class="checkbox-description">
															{!! trans('messages.' . $help_class . '.' . $name . '.help') !!}
														</span>
													@endif
													
												</label>

											