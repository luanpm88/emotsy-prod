@if ($products->count() > 0)
	<div class="row mt-4">
		@foreach ($products as $key => $product)
			@php
				$wooProduct = wc_get_product($product->ID);
			@endphp
			<div class="col-md-3 col-sm-6 mb-4">
				<div class="card mb-4 shadow-sm">
					<span class="product-image-box woo-image border-bottom">
						{!! $wooProduct->get_image() !!}
					</span>
					<div class="card-body p-3">
						<h5 title="{{ $product->title }}" class="fw-600 mt-1 mb-2 text-ellipsis">{{ $wooProduct->name }}</h5>
						<p style="display: block;
						height: 49px;
						overflow: hidden;" class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
						<div class="">
							<div class="d-flex align-items-center">
								<a href="{{ action('Site\ProductController@edit', $wooProduct->id) }}" role="button" class="btn btn-secondary">
									{{ trans('messages.edit') }}
								</a>
								<a
									class="btn btn-link list-action-single"
									link-confirm="{{ trans('messages.product.delete.confirm', [
										'name' => $wooProduct->name,
									]) }}"
									link-method="POST"
									href="{{ action('Site\ProductController@delete', $wooProduct->id) }}">
									{{ trans('messages.delete') }}
								</a>
								<span class="text-muted ml-auto text-primary m-icon d-flex align-items-center">
									{{-- <img width="20px" class="mr-2 list-source-img" src="{{ url('images/woo_list.png') }}" /> --}}
									<span class="material-icons-round">
										qr_code
										</span>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	</div>
	
	@include('elements/_per_page_select', ["items" => $products])
		
@elseif (!empty(request()->keyword))
	<div class="empty-list">
		<span class="material-icons-outlined">
			category
			</span>
		<span class="line-1">
			{{ trans('messages.no_search_result') }}
		</span>
	</div>
@else
	<div class="empty-list">
		<span class="material-icons-outlined">
			category
			</span>
		<span class="line-1 text-muted">
			<p>{!! trans('messages.product.no_product') !!}</p>
		</span>
	</div>
@endif
