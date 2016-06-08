@extends('layouts.frontend')

@section('page_header')
	<div class="page-header">
		<div class="page-header-content">			
			<div class="page-title">
				<a href="/create_list" type="button" class="btn bg-grey-600 pull-right">Create list</a>
				<h1>
					<span class="text-semibold">My lists</span>
				</h1>
			</div>
		</div>
	</div>
@endsection

@section('content')
				<div class="filter-box">
					<input type="checkbox" class="styled">
					<span class="filter-group">
						<span class="title text-semibold text-muted">Sort by</span>
						<div class="btn-group dropdown">
							<button type="button" class="btn bg-grey-600 dropdown-toggle" data-toggle="dropdown">Custom order &nbsp;<span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right">
								<li class="active"><a href="#">Custom order</a></li>
								<li><a href="#">Name</a></li>
								<li><a href="#">Created at</a></li>
							</ul>
						</div>
					</span>
				</div>
				<div class="">					
					<table class="table table-box">
						@for ($i = 0; $i < 3; $i++)
							<tr>
								<td width="1%">
									<div class="text-nowrap">
										<i class="icon icon-more list-drag-button"></i>
										<input type="checkbox" class="styled">
									</div>
								</td>
								<td>
									<h5 class="no-margin text-bold"><a href="#">Mail list {{ $i }}</a></h5>
									<span class="text-muted">Created at: Jun 22, 2013 10:09 am</span>
									<br />
									<span class="text-muted2">Not rated yet</span>
								</td>
								<td>
									<div class="single-stat-box pull-left">
										<span class="no-margin stat-num">{{ rand(0, 1000) }}</span>
										<br />
										<span class="text-muted">Subscribers</span>
									</div>
									<div class="single-stat-box pull-left">
										<span class="no-margin stat-num">{{ rand(0, 100) }}.0%</span>
										<br />
										<span class="text-muted">Opens</span>
									</div>
									<div class="single-stat-box pull-left">
										<span class="no-margin stat-num">{{ rand(0, 100) }}.0%</span>
										<br />
										<span class="text-muted">Clicks</span>
									</div>
									<br style="clear:both" />
								</td>
								<td class="text-right">
									<button data-popup="tooltip" title="Add subscriber" type="button" class="btn bg-teal btn-icon"><i class="icon icon-plus2"></i><i class="glyphicon glyphicon-user"></i></button>
									<div class="btn-group">
										<button type="button" class="btn bg-teal btn-icon">Overview</button>
										<button type="button" class="btn bg-teal dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
										<ul class="dropdown-menu dropdown-menu-right">
											<li><a href="#">Manage subscribers</a></li>
											<li><a href="#">Signup forms</a></li>
											<li><a href="#">Settings</a></li>
											<li><a href="#">Import</a></li>
											<li><a href="#">Exports</a></li>
											<li><a href="#">Duplicate list</a></li>
											<li><a href="#">Combine lists</a></li>
										</ul>
									</div>
								</td>
							</tr>
						@endfor
					</table>
				</div>
@endsection
