@extends('layouts.frontend')

@section('page_header')
	<div class="page-header">
		<div class="page-header-content">			
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="/">Home</a></li>
					<li><a href="/lists">Lists</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-plus-circle2"></i> Create list</span>
				</h1>				
			</div>
		</div>
	</div>
@endsection

@section('content')
                
				
                
				<div class="row">
					<div class="col-md-6">
						<div class="sub_section">
							<h2 class="text-semibold text-teal-800">List details</h2>
							
							<form>							
								<div class="form-group">
									<label>List name:</label>
									<input type="text" class="form-control">
									<div class="help alert alert-info">
										<span>Your subscribers will see this, so make it something appropriate.</span><br> <span>Good example: “Acme Company Newsletter”</span><br> <span>Bad example: “Cust_11_01_2007”</span>
									</div>
								</div>
								<div class="form-group">
									<label for="from_email">Default <span class="keyword">From email address</span>:</label>
									<input type="text" class="form-control">
									<div class="help alert alert-info">
										This is the address people will reply to.
									</div>
								</div>
								<div class="form-group">
									<label for="from_email">Default <span class="keyword">From name</span>:</label>
									<input type="text" class="form-control">
									<div class="help alert alert-info">
										This is who your emails will come from. Use something they'll instantly recognize, like your company name.
									</div>
								</div>
								<div class="form-group">
									<label for="from_email">Remind people how they signed up to your list</label>
									<select data-placeholder="Type to search" class="select-search">
										<option value="">Reuse a reminder from another list</option>
										<option value="296621">List 1</option>
										<option value="33197">Myself</option>
									</select>
									<br /><br />
									<textarea placeholder="Write a short reminder about how the recipient joined your list." class="form-control"></textarea>
									<div class="help alert alert-info">
										Example: “You are receiving this email because you opted in at our website … ” or “We send special offers to customers who opted in at … ”
									</div>
								</div>																
							</form>
						</div>	
					</div>
					<div class="col-md-6">
						<div class="sub_section">
							<h2 class="text-semibold text-teal-800">Notifications</h2>
							
							<div class="form-group">
								<label>How to send notification</label>
								<div class="checkbox">
									<label>
										<input type="checkbox" class="styled">
										Daily summary
										<span class="checkbox-description">Summary of subscribe/unsubscribe activity</span>
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" class="styled">
										One-by-one
										<span class="checkbox-description">Subscribe notifications as they happen</span>
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" class="styled">
										One-by-one
										<span class="checkbox-description">Unsubscribe notifications as they happen</span>
									</label>
								</div>
							</div>
								
						</div>						
					</div>
						
				</div>
				
				<div class="text-right">
					<button class="btn bg-success-800"><i class="icon-check"></i> Save</button>
					<a href="/lists" class="btn bg-grey-800"><i class="icon-cross2"></i> Cancel</a>
				</div>
					
				
@endsection
