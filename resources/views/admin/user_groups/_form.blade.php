                    <div class="sub_section">
						<div class="row">
							<div class="col-md-12">									

									@include('helpers.form_control', ['type' => 'text', 'name' => 'name', 'value' => $group->name, 'help_class' => 'user_group', 'rules' => Mailixa\Model\UserGroup::$rules])

							</div>
							
						</div>
					</div>
					
					