@extends('layouts.frontend')

@section('title', $list->name . "- " . trans('messages.manage_list_fields') )
	
@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
		
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection

@section('page_header')
	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li><a href="{{ action("MailListController@index") }}">{{ trans('messages.lists') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-list3"></i> {{ $list->name }}</span>
				</h1>
			</div>

@endsection

@section('content')
                
                @include("lists._menu")
				
                <h2 class="text-bold">{{ trans('messages.manage_list_fields') }}</h2>
                    
                <p>{!! trans('messages.fields_intro') !!}</p>
                
                <div class="listing-form"                    
					sort-url="{{ action('FieldController@sort', $list->uid) }}"
					per-page="1"
                >
                    @if ($fields->count() > 0)
                        <table class="table table-box table-box-head"
                            current-page="1"
                        >
                            <tr>
                                <th>{{ trans('messages.field_label') }}</th>
                                <th>{{ trans('messages.reqired?') }}</th>
                                <th>{{ trans('messages.visible?') }}</th>
                                <th>{{ trans('messages.tag') }}</th>
                                <th>{{ trans('messages.default_value') }}</th>
                            </tr>
                            @foreach ($fields as $key => $item)
                                <tr>
                                    <td>
                                        <input type="hidden" class="node styled"
                                            custom-order="{{ $item->custom_order }}"
                                            value="{{ $item->uid }}"
                                        />
                                        <i data-action="move" class="icon icon-more2 list-drag-button"></i>
                                        {{ trans('messages.field_label') }}
                                    </td>
                                    <td>{{ trans('messages.reqired?') }}</td>
                                    <td>{{ trans('messages.visible?') }}</td>
                                    <td>{{ trans('messages.tag') }}</td>
                                    <td>{{ trans('messages.default_value') }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @elseif (!empty(request()->keyword))
                        <div class="empty-list">
                            <i class="icon-address-book2"></i>
                            <span class="line-1">
                                {{ trans('messages.no_search_result') }}
                            </span>
                        </div>
                    @else					
                        <div class="empty-list">
                            <i class="icon-address-book2"></i>
                            <span class="line-1">
                                {{ trans('messages.list_empty_line_1') }}
                            </span>
                            <span class="line-2">
                                {{ trans('messages.list_empty_line_2') }}
                            </span>
                        </div>
                    @endif
                </div>
@endsection
