@extends('layouts.reseller')

@section('title', trans('messages.dashboard'))

@section('page_script')    
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/visualization/echarts/echarts.js') }}"></script>
    
    <script type="text/javascript" src="{{ URL::asset('js/chart.js') }}"></script>
@endsection

@section('content')
	<h6 class="h1-subtitle">{{ trans('messages.reseller') }}</h6>
    <h1 class="mt-0">{{ trans('messages.dashboard') }}</h1>
	
	<div class="row">
		<div class="col-md-7">
			<div class="row">
				@if (
					Auth::user()->can("read", new Acelle\Model\Customer())
				)
					<div class="col-md-6">
						<h2 class="text-muted">{{ trans('messages.customer') }}</h2>
						@if (Auth::user()->can("read", new Acelle\Model\Customer()))
							<a class="dashboard-box" href="{{ action('Admin\ResellerController@index') }}">
								<span class="badge badge-info bg-teal-800">{{ Auth::user()->reseller->customers->count() }}</span>
								<div class="dashboard-icon">
									<i class="icon-users"></i>
								</div>
								<h4>{{ trans('messages.customers') }}</h4>
							</a>
						@endif
						<br style="clear:both" />
					</div>
				@endif
			</div>
		</div>
		<div class="col-md-5">
			<div class="panel-group content-group-lg" id="accordion1">
				<div class="panel panel-white panel-no-color">
					<div class="panel-heading">
						<h5 class="panel-title text-semibold">
							<a data-toggle="collapse" data-parent="#accordion1" href="#accordion-group1">{{ trans('messages.recent_resellers') }}</a>
							<i class="icon-menu-open"></i>
						</h5>
					</div>
					<div id="accordion-group1" class="panel-collapse collapse in">
						<div class="panel-body">
							
						</div>
					</div>
				</div>

				<div class="panel panel-white panel-no-color">
					<div class="panel-heading">
						<h5 class="panel-title text-semibold">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion1" href="#accordion-group2">{{ trans('messages.recent_customers') }}</a>
							<i class="icon-menu-open"></i>
						</h5>
					</div>
					<div id="accordion-group2" class="panel-collapse collapse">
						<div class="panel-body">
							Тon cupidatat skateboard dolor brunch. Тesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda.
						</div>
					</div>
				</div>

				<div class="panel panel-white panel-no-color">
					<div class="panel-heading">
						<h5 class="panel-title text-semibold">
							<a class="collapsed" data-toggle="collapse" data-parent="#accordion1" href="#accordion-group3">{{ trans('messages.activities') }}</a>
							<i class="icon-menu-open"></i>
						</h5>
					</div>
					<div id="accordion-group3" class="panel-collapse collapse">
						<div class="panel-body">
							3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		
    
@endsection
