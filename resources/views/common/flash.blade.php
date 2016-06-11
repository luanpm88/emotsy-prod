                    <!-- Display flash messages -->
					<div class="flash-message header-flash">					
						@foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            
							@if(Session::has('alert-' . $msg))
                            
							<p class="alert alert-{{ $msg }} alert-styled-left alert-arrow-left alert-bordered">
								<button data-dismiss="alert" class="close" type="button"><span>×</span><span class="sr-only">Close</span></button>
								{{ Session::get('alert-' . $msg) }}
							</p>
							
						  @endif
						@endforeach
					</div>
					<!-- end .flash-message -->