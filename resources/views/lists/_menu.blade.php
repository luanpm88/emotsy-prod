                                    <div class="row">
										<div class="col-md-12">
											<ul class="nav nav-tabs nav-tabs-top page-second-nav">
                                                <li class="dropdown">
													<a href="" class="level-1">
														<i class="icon-stats-bars3"></i> {{ trans('messages.overview') }}
													</a>
												</li>
												<li rel0="AccountController/contact" class="dropdown">
													<a href="{{ action("AccountController@contact") }}" class="level-1" data-toggle="dropdown">
														<i class="icon-users4 position-left"></i> {{ trans('messages.subscribers') }}
                                                        <span class="caret"></span>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li>
                                                            <a href="" data-toggle="tab">
                                                                <i class="icon-list2"></i> {{ trans('messages.view_all') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="" data-toggle="tab">
                                                                <i class="icon-plus2"></i> {{ trans('messages.add') }}
                                                            </a>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="" data-toggle="tab">
                                                                <i class="icon-download4"></i> {{ trans('messages.import') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="" data-toggle="tab">
                                                                <i class="icon-upload4"></i> {{ trans('messages.export') }}
                                                            </a>
                                                        </li>
                                                    </ul>
												</li>
                                                <li rel0="AccountController/contact" class="dropdown">
													<a href="{{ action("AccountController@contact") }}" class="level-1" data-toggle="dropdown">
														<i class="icon-make-group position-left"></i> {{ trans('messages.segments') }}
                                                        <span class="caret"></span>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li>
                                                            <a href="" data-toggle="tab">
                                                                <i class="icon-list2"></i> {{ trans('messages.view_all') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="" data-toggle="tab">
                                                                <i class="icon-plus2"></i> {{ trans('messages.add') }}
                                                            </a>
                                                        </li>
                                                    </ul>
												</li>
                                                <li rel0="AccountController/contact" class="dropdown">
													<a href="{{ action("AccountController@contact") }}" class="level-1" data-toggle="dropdown">
														<i class="icon-certificate position-left"></i> {{ trans('messages.custom_forms_and_emails') }}
                                                        <span class="caret"></span>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-right has-head">
                                                        <li class="head">
                                                            <i class="icon-enter"></i> {{ trans('messages.subscribe') }}
                                                        </li>
                                                        <li>
                                                            <a href="" data-toggle="tab">
                                                                {{ trans('messages.sign_up_form') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="" data-toggle="tab">
                                                                {{ trans('messages.sign_up_thankyou_page') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="" data-toggle="tab">
                                                                {{ trans('messages.final_welcome_email') }}
                                                            </a>
                                                        </li>
                                                        <li class="head">
                                                            <i class="icon-exit"></i> {{ trans('messages.unsubscribe') }}
                                                        </li>
                                                        <li>
                                                            <a href="" data-toggle="tab">
                                                                {{ trans('messages.unsubscribe_form') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="" data-toggle="tab">
                                                                {{ trans('messages.unsubscribe_success_page') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="" data-toggle="tab">
                                                                {{ trans('messages.goodbye_email') }}
                                                            </a>
                                                        </li>
                                                        <li class="head">
                                                            <i class="icon-profile"></i> {{ trans('messages.update_profile') }}
                                                        </li>
                                                        <li>
                                                            <a href="" data-toggle="tab">
                                                                {{ trans('messages.update_profile_form') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="" data-toggle="tab">
                                                                {{ trans('messages.update_profile_success_page') }}
                                                            </a>
                                                        </li>
                                                    </ul>
												</li>
												<li rel0="FieldController/index" class="level-1">
														<a href="{{ action('FieldController@index', $list->uid) }}">
															<i class="icon-list3"></i> {{ trans('messages.manage_list_fields') }}
														</a>
													</li>
												<li rel0="MailListController/edit" class="level-1">
													<a href="{{ action('MailListController@edit', $list->uid) }}">
														<i class="icon-pencil7"></i> {{ trans('messages.list_information') }}
													</a>
												</li>                                                
											</ul>
										</div>
									</div>