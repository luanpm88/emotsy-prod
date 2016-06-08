<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Limitless - Responsive Web Application Kit by Eugene Kopyov</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="assets/css/colors.css" rel="stylesheet" type="text/css">
	<link href="css/app.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/ui/nicescroll.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/ui/drilldown.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script type="text/javascript" src="assets/js/plugins/visualization/d3/d3.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
	<script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
	<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
	<script type="text/javascript" src="assets/js/plugins/pickers/daterangepicker.js"></script>

	<script type="text/javascript" src="assets/js/core/app.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
	<!-- /theme JS files -->

</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="index.html"><img src="assets/images/logo_light.png" alt=""></a>

			<ul class="nav navbar-nav pull-right visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-menu7"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li class=""><a href="index.html">Campaigns</a></li>
                <li class=""><a href="index.html">Templates</a></li>
                <li class="active"><a href="index.html">Lists</a></li>
                <li class=""><a href="index.html">Reports</a></li>
                <li class=""><a href="index.html">Automation</a></li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown language-switch">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="assets/images/flags/gb.png" class="position-left" alt="">
						English
						<span class="caret"></span>
					</a>

					<ul class="dropdown-menu">
						<li><a class="deutsch"><img src="assets/images/flags/de.png" alt=""> Deutsch</a></li>
						<li><a class="ukrainian"><img src="assets/images/flags/ua.png" alt=""> Українська</a></li>
						<li><a class="english"><img src="assets/images/flags/gb.png" alt=""> English</a></li>
						<li><a class="espana"><img src="assets/images/flags/es.png" alt=""> España</a></li>
						<li><a class="russian"><img src="assets/images/flags/ru.png" alt=""> Русский</a></li>
					</ul>
                </li>

				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="assets/images/placeholder.jpg" alt="">
						<span>Victoria</span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="#"><i class="icon-user-plus"></i> My profile</a></li>
						<li><a href="#"><i class="icon-coins"></i> My balance</a></li>
						<li><a href="#"><span class="badge badge-warning pull-right">58</span> <i class="icon-comment-discussion"></i> Messages</a></li>
						<li class="divider"></li>
						<li><a href="#"><i class="icon-cog5"></i> Account settings</a></li>
						<li><a href="#"><i class="icon-switch2"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->
	

	<!-- Page header -->
	<div class="page-header">
		<div class="page-header-content">			
			<div class="page-title">
				<button type="button" class="btn bg-grey-600 pull-right">Create list</button>
				<h1>
					<span class="text-semibold">My lists</span>
				</h1>
			</div>
		
			
		</div>
	</div>
	<!-- /page header -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">
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
						<tr>
							<td width="1%">
								<div class="text-nowrap">
									<i class="icon icon-more list-drag-button"></i>
									<input type="checkbox" class="styled">
								</div>
							</td>
							<td>
								<h4 class="no-margin text-semibold"><a href="#">Mail list #1</a></h4>
								<span class="text-muted">Created at: Jun 22, 2013 10:09 am</span>
								<br />
								<span class="text-muted2">Not rated yet</span>
							</td>
							<td>
								<div class="single-stat-box pull-left">
									<h4 class="no-margin text-semibold"><strong>100</strong></h5>
									<span class="text-muted">Subscribers</span>
								</div>
								<div class="single-stat-box pull-left">
									<h4 class="no-margin text-semibold"><strong>90.0%</strong></h5>
									<span class="text-muted">Opens</span>
								</div>
								<div class="single-stat-box pull-left">
									<h4 class="no-margin text-semibold"><strong>10.0%</strong></h5>
									<span class="text-muted">Clicks</span>
								</div>
								<br style="clear:both" />
							</td>
							<td class="text-right">
								<button type="button" class="btn bg-teal btn-icon"><i class="icon icon-user-plus"></i></button>
								<div class="btn-group">
									<button type="button" class="btn bg-teal btn-icon">Overview</button>
			                    	<button type="button" class="btn bg-teal dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
			                    	<ul class="dropdown-menu dropdown-menu-right">
										<li><a href="#">Manage subscribers</a></li>
										<li class="divider"></li>
										<li><a href="#">Signup forms</a></li>
										<li class="divider"></li>
										<li><a href="#">Settings</a></li>
										<li class="divider"></li>
										<li><a href="#">Import</a></li>
										<li><a href="#">Exports</a></li>
										<li><a href="#">Duplicate list</a></li>
										<li><a href="#">Combine lists</a></li>
									</ul>
								</div>
							</td>
						</tr>
						<tr>
							<td width="1%">
								<div class="text-nowrap">
									<i class="icon icon-more list-drag-button"></i>
									<input type="checkbox" class="styled">
								</div>
							</td>
							<td>
								<h4 class="no-margin text-semibold"><a href="#">Mail list #1</a></h4>
								<span class="text-muted">Created at: Jun 22, 2013 10:09 am</span>
								<br />
								<span class="text-muted2">Not rated yet</span>
							</td>
							<td>
								<div class="single-stat-box pull-left">
									<h4 class="no-margin text-semibold"><strong>100</strong></h5>
									<span class="text-muted">Subscribers</span>
								</div>
								<div class="single-stat-box pull-left">
									<h4 class="no-margin text-semibold"><strong>90.0%</strong></h5>
									<span class="text-muted">Opens</span>
								</div>
								<div class="single-stat-box pull-left">
									<h4 class="no-margin text-semibold"><strong>10.0%</strong></h5>
									<span class="text-muted">Clicks</span>
								</div>
								<br style="clear:both" />
							</td>
							<td class="text-right">
								<button type="button" class="btn bg-teal btn-icon"><i class="icon icon-user-plus"></i></button>
								<div class="btn-group">									
									<button type="button" class="btn bg-teal btn-icon">Overview</button>
			                    	<button type="button" class="btn bg-teal dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
			                    	<ul class="dropdown-menu dropdown-menu-right">
										<li><a href="#">Manage subscribers</a></li>
										<li class="divider"></li>
										<li><a href="#">Signup forms</a></li>
										<li class="divider"></li>
										<li><a href="#">Settings</a></li>
										<li class="divider"></li>
										<li><a href="#">Import</a></li>
										<li><a href="#">Exports</a></li>
										<li><a href="#">Duplicate list</a></li>
										<li><a href="#">Combine lists</a></li>
									</ul>
								</div>
							</td>
						</tr>
						<tr>
							<td width="1%">
								<div class="text-nowrap">
									<i class="icon icon-more list-drag-button"></i>
									<input type="checkbox" class="styled">
								</div>
							</td>
							<td>
								<h4 class="no-margin text-semibold"><a href="#">Mail list #1</a></h4>
								<span class="text-muted">Created at: Jun 22, 2013 10:09 am</span>
								<br />
								<span class="text-muted2">Not rated yet</span>
							</td>
							<td>
								<div class="single-stat-box pull-left">
									<h4 class="no-margin text-semibold"><strong>100</strong></h5>
									<span class="text-muted">Subscribers</span>
								</div>
								<div class="single-stat-box pull-left">
									<h4 class="no-margin text-semibold"><strong>90.0%</strong></h5>
									<span class="text-muted">Opens</span>
								</div>
								<div class="single-stat-box pull-left">
									<h4 class="no-margin text-semibold"><strong>10.0%</strong></h5>
									<span class="text-muted">Clicks</span>
								</div>
								<br style="clear:both" />
							</td>
							<td class="text-right">
								<button type="button" class="btn bg-teal btn-icon"><i class="icon icon-user-plus"></i></button>
								<div class="btn-group">
									<button type="button" class="btn bg-teal btn-icon">Overview</button>
			                    	<button type="button" class="btn bg-teal dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
			                    	<ul class="dropdown-menu dropdown-menu-right">
										<li><a href="#">Manage subscribers</a></li>
										<li class="divider"></li>
										<li><a href="#">Signup forms</a></li>
										<li class="divider"></li>
										<li><a href="#">Settings</a></li>
										<li class="divider"></li>
										<li><a href="#">Import</a></li>
										<li><a href="#">Exports</a></li>
										<li><a href="#">Duplicate list</a></li>
										<li><a href="#">Combine lists</a></li>
									</ul>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->


		<!-- Footer -->
		<div class="footer text-muted">
			&copy; 2015. <a href="#">Limitless Web App Kit</a> by <a href="http://themeforest.net/user/Kopyov" target="_blank">Eugene Kopyov</a>
		</div>
		<!-- /footer -->

	</div>
	<!-- /page container -->

</body>
</html>
