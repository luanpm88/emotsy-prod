                                    <div class="row">
										<div class="col-md-12">
											<ul class="nav nav-tabs nav-tabs-top page-second-nav">
												<li rel0="AccountController/profile">
													<a href="{{ action("AccountController@profile") }}" class="level-1">
														<i class="icon-user position-left"></i> {{ trans('messages.my_profile') }}
													</a>
												</li>
												<li rel0="AccountController/contact">
													<a href="{{ action("AccountController@contact") }}" class="level-1">
														<i class="icon-office position-left"></i> {{ trans('messages.contact_information') }}
													</a>
												</li>
												<li rel0="AccountController/logs">
													<a href="{{ action("AccountController@logs") }}" class="level-1">
														<i class="icon-history position-left"></i> {{ trans('messages.logs') }}
													</a>
												</li>
											</ul>
										</div>
									</div>