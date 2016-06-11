                    <!-- Display flash messages -->
					<div class="flash-message">					
						@foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            
							@if(Session::has('alert-' . $msg))
                            
							<p class="alert bg-{{ $msg }}">
								<button data-dismiss="alert" class="close" type="button"><span>×</span><span class="sr-only">Close</span></button>
								{{ Session::get('alert-' . $msg) }}
							</p>
							
						  @endif
						@endforeach
					</div>
					<!-- end .flash-message -->