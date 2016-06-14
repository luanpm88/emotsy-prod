<div class="text_sample">
                            <table>
                                <tr>
                                    <td>
										<input type="hidden" class="custom_order"
                                            value="__index__"
											name="fields[__index__][custom_order]"
                                        />
										<input type="hidden"
                                            value="__index__"
											name="fields[__index__][uid]"
                                        />
										<i data-action="move" class="icon icon-more2 list-drag-button"></i>
									</td>
                                    <td class="text-nowrap">                                        
                                        @include('helpers.form_control', ['type' => 'text', 'name' => 'fields[__index__][label]', 'label' => '', 'subfix' => '__type__', 'value' => '', 'help_class' => 'field'])
                                    </td>
                                    <td class="text-nowrap">
										@include('helpers.form_control', ['type' => 'checkbox', 'name' => 'fields[__index__][required]', 'label' => '', 'value' => '', 'options' => [false,true], 'help_class' => 'field'])
									</td>
                                    <td class="text-nowrap">
										@include('helpers.form_control', ['type' => 'checkbox', 'name' => 'fields[__index__][visible]', 'label' => '', 'value' => true, 'options' => [false,true], 'help_class' => 'field'])
									</td>
                                    <td class="text-nowrap">
										@include('helpers.form_control', ['type' => 'text', 'name' => 'fields[__index__][tag]', 'label' => '', 'value' => '', 'help_class' => 'field', 'prefix' => "[", 'subfix' => "]"])
									</td>
                                    <td class="text-nowrap">
										@include('helpers.form_control', ['type' => 'text', 'name' => 'fields[__index__][default_value]', 'label' => '', 'value' => '', 'help_class' => 'field'])
									</td>
									<td>
										<a href="#delete" class="btn bg-danger-400"><i class="icon-trash"></i></a>
									</td>
                                </tr>
                            </table>
</div>