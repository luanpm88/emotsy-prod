@extends('layouts.frontend')

@section('title', $campaign->name)
	
@section('page_script')    

@endsection

@section('page_header')
	
			@include("campaigns._header")

@endsection

@section('content')
                
            @include("campaigns._menu")

            <h3 class="mt-10"><span class="text-teal text-semibold">{{ count($campaign->getLinks()) }}</span> {{ trans('messages.links') }}</h3>
            
            <table class="table table-box pml-table table-head">
                <tr>
                    <th>{{ trans('messages.url') }}</th>
                    <th class="text-right">{{ trans('messages.total_clicks') }}</th>
                    <th class="text-right">{{ trans('messages.unique_clicks') }}</th>
                </tr>
				@foreach ($campaign->getLinks() as $link)
					<tr>
						<td>
							<a href="">
								{{ $link->url }}
							</a>
						</td>
						<td class="text-right">
							1 (100%)
						</td>
						<td class="text-right">
							1 (100%)
						</td>
					</tr>
				@endforeach
            </table>
@endsection
