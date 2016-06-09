@extends('layouts.frontend')

@section('page_header')
	<div class="page-header">
		<div class="page-header-content">			
			<div class="page-title">
				<h1>
					<span class="text-semibold">Create list</span>
				</h1>
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="/">Home</a></li>
					<li><a href="/lists">Lists</a></li>
					<li class="active">Create new</li>
				</ul>
			</div>
		</div>
	</div>
@endsection

@section('content')
                <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/full.min.js"></script>
                <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
            
                <script type="text/javascript" src="assets/js/pages/form_select2.js"></script>
				
				<h2>List details</h2>
                
                <form>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>List name</label>
								<input type="text" class="form-control">
								<div class="help alert alert-info">
									<span>Your subscribers will see this, so make it something appropriate.</span><br> <span>Good example: “Acme Company Newsletter”</span><br> <span>Bad example: “Cust_11_01_2007”</span>
								</div>
							</div>
							<div class="form-group">
								<label for="from_email">Default <span class="keyword">From email address</span></label>
								<input type="text" class="form-control">
								<div class="help alert alert-info">
									<span>Your subscribers will see this, so make it something appropriate.</span><br> <span>Good example: “Acme Company Newsletter”</span><br> <span>Bad example: “Cust_11_01_2007”</span>
								</div>
							</div>
							<div class="form-group">
								<label for="from_email">Default <span class="keyword">From name</span></label>
								<input type="text" class="form-control">
								<div class="help alert alert-info">
									<span>Your subscribers will see this, so make it something appropriate.</span><br> <span>Good example: “Acme Company Newsletter”</span><br> <span>Bad example: “Cust_11_01_2007”</span>
								</div>
							</div>
							<div class="form-group">
								<label for="from_email">Remind people how they signed up to your list</label>
								<select data-placeholder="Type 'aero'" class="select">
									<option>Aeróbics</option>
									<option>Aeróbics en Agua</option>
									<option>Aerografía</option>
									<option>Aeromodelaje</option>
									<option>Águilas</option>
									<option>Ajedrez</option>
									<option>Ala Delta</option>
									<option>Álbumes de Música</option>
									<option>Alusivos</option>
									<option>Análisis de Escritura a Mano</option>
								</select>
								<br /><br />
								<textarea placeholder="Write a short reminder about how the recipient joined your list." class="form-control"></textarea>
								<div class="help alert alert-info">
									<span>Your subscribers will see this, so make it something appropriate.</span><br> <span>Good example: “Acme Company Newsletter”</span><br> <span>Bad example: “Cust_11_01_2007”</span>
								</div>
							</div>
						</div>
					</div>							
                </form>
@endsection
