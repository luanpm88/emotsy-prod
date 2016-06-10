@extends('layouts.clean')

@section('content')
    
                <!-- Advanced login -->
				<form class="" role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}
                    
                    <div class="text-center login-header">
                        <a class="main-logo-big" href="{{ url('HomeController@index') }}">
                            <img src="{{ URL::asset('images/logo_big.png') }}" alt="">
                        </a>
                    </div>
                    
					<div class="panel panel-body">						

						<div class="form-group has-feedback has-feedback-left{{ $errors->has('email') ? ' has-error' : '' }}">
							<input id="email" type="email" class="form-control" name="email" placeholder="{{ trans("messages.email") }}" value="{{ old('email') }}">
							<div class="form-control-feedback">
								<i class="glyphicon glyphicon-envelope text-muted"></i>
							</div>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif                            
						</div>

						<div class="form-group has-feedback has-feedback-left">
							<input id="password" type="password" class="form-control" name="password" placeholder="{{ trans("messages.password") }}">
							<div class="form-control-feedback">
								<i class="icon-lock2 text-muted"></i>
							</div>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
						</div>

						<div class="form-group login-options">
							<div class="row">
								<div class="col-sm-6">
									<label class="checkbox-inline">
										<input type="checkbox" class="styled" checked="checked">
										{{ trans("messages.stay_logged_in") }}
									</label>
								</div>

								<div class="col-sm-6 text-right">
									<a href="{{ url('/password/reset') }}">{{ trans("messages.forgot_password") }}</a>
								</div>
							</div>
						</div>

						<button type="submit" class="btn btn-lg bg-teal btn-block">{{ trans("messages.login") }} <i class="icon-circle-right2 position-right"></i></button>
						
					</div>
				</form>
				<!-- /advanced login -->
    
@endsection
