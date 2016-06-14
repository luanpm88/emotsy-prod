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
                
				@if ($errors->has("fields.miss_main_tag"))
					<div class="text-danger text-semibold">
						<strong>{{ $errors->first("fields.miss_main_tag") }}</strong>
					</div>
				@endif
				@if ($errors->has("fields.conflict_tag"))
					<div class="text-danger text-semibold">
						<strong>{{ $errors->first("fields.conflict_tag") }}</strong>
					</div>
				@endif
				
                <form action="{{ action('FieldController@store', $list->uid) }}" class="listing-form"                    
					sort-urla="{{ action('FieldController@sort', $list->uid) }}"
					per-page="1"
					method="POST"
                >
					{{ csrf_field() }}
					
                    @if ($fields->count() > 0)
                        <table class="table table-box table-box-head field-list"
                            current-page="1"
                        >
								<th width="1%"></th>
                                <th>{{ trans('messages.field_label_and_type') }}</th>
                                <th>{{ trans('messages.required?') }}</th>
                                <th>{{ trans('messages.visible?') }}</th>
                                <th>{{ trans('messages.tag') }}</th>
                                <th>{{ trans('messages.default_value') }}</th>
								<th></th>
                            @foreach ($fields as $key => $item)
                                <tr>
									<td>
										<input type="hidden" class="custom_order"
                                            value="{{ $item->custom_order }}"
											name="fields[{{ $item->uid }}][custom_order]"
                                        />
										<input type="hidden" class="node"
                                            custom-order="{{ $item->custom_order }}"
                                            value="{{ $item->uid }}"
											name="fields[{{ $item->uid }}][uid]"
                                        />
										<i data-action="move" class="icon icon-more2 list-drag-button"></i>
									</td>
                                    <td class="text-nowrap">                                        
                                        @include('helpers.form_control', ['type' => 'text', 'name' => 'fields[' . $item->uid . '][label]', 'label' => '', 'subfix' => trans('messages.' . $item->type), 'value' => $item->label, 'help_class' => 'field'])
                                    </td>
                                    <td class="text-nowrap">
										@include('helpers.form_control', ['type' => 'checkbox', 'name' => 'fields[' . $item->uid . '][required]', 'label' => '', 'value' => $item->required, 'options' => [false,true], 'help_class' => 'field'])
									</td>
                                    <td class="text-nowrap">
										@include('helpers.form_control', ['type' => 'checkbox', 'name' => 'fields[' . $item->uid . '][visible]', 'label' => '', 'value' => $item->visible, 'options' => [false,true], 'help_class' => 'field'])
									</td>
                                    <td class="text-nowrap">
										@include('helpers.form_control', ['type' => 'text', 'name' => 'fields[' . $item->uid . '][tag]', 'label' => '', 'value' => $item->tag, 'help_class' => 'field', 'prefix' => "[", 'subfix' => "]"])
									</td>
                                    <td class="text-nowrap">
										@include('helpers.form_control', ['type' => 'text', 'name' => 'fields[' . $item->uid . '][default_value]', 'label' => '', 'value' => $item->default_value, 'help_class' => 'field'])
									</td>
									<td>
										<a href="#delete" class="btn bg-danger-400"><i class="icon-trash"></i></a>
									</td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
					<br />
					<h4>{{ trans('messages.add_field') }}</h4>
					<div>                                    
						<span sample-url="{{ action("FieldController@sample", ['list_uid' => $list->uid, "type" => "text"]) }}" class="btn btn-default btn-xs add-custom-field-button mr-10" type_name="text">
							<i class="icon-font-size2"></i> {{ trans('messages.text_field') }}
						</span>
						<span class="btn btn-default btn-xs add-custom-field-button mr-10" type_name="dropdown">
							<i class="icon-menu2"></i> {{ trans('messages.dropdown_field') }}
						</span>
						<span class="btn btn-default btn-xs add-custom-field-button mr-10" type_name="multiselect"><i class="icon-menu3"></i> {{ trans('messages.multiselect_field') }}</span>
						<span class="btn btn-default btn-xs add-custom-field-button mr-10" type_name="checkbox"><i class="icon-checkbox-partial2"></i> {{ trans('messages.checkbox_field') }}</span>
						<span class="btn btn-default btn-xs add-custom-field-button mr-10" type_name="radio"><i class="icon-circles"></i> {{ trans('messages.radio_field') }}</span>
						<span class="btn btn-default btn-xs add-custom-field-button mr-10" type_name="date"><i class="icon-calendar52"></i> {{ trans('messages.date_field') }}</span>
						<span class="btn btn-default btn-xs add-custom-field-button mr-10" type_name="datetime"><i class="icon-alarm"></i> {{ trans('messages.datetime_field') }}</span>
						<span class="btn btn-default btn-xs add-custom-field-button" type_name="textarea"><i class="icon-menu6"></i> {{ trans('messages.textarea_field') }}</span>
					</div>					
						
					<hr /><br />
					<div class="">
						<button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.save_change') }}</button>
						<a href="{{ trans('messages.cancel') }}" class="btn bg-grey-800"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
					</div>
                </form>
@endsection
