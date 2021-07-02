@extends('layouts.frontend')

@section('title', $list->name . ": " . trans('messages.export'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>

	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
@endsection

@section('page_header')

			@include("lists._header")

@endsection

@section('content')

	@include("lists._menu")

	<h2 class="text-bold text-teal-800"><i class="icon-users4"></i> {{ trans('messages.export_subscribers') }}</h2>

	<div class="row">
		<div class="col-md-12">
			<form
				process-url="{{ action('SubscriberController@exportProccess', $list->uid) }}"
				action="{{ action('SubscriberController@export', $list->uid) }}" method="POST" class="ajax_upload_form form-validate-jquery">
				{{ csrf_field() }}

				<div class="upload_file before">
					<p>{!! trans('messages.click_to_start_export', ['total' => $list->readCache('SubscriberCount', 0)]) !!}</p>

					<div class="form-group control-radio">
						<div class="radio_box mb-4" data-popup='tooltip' title="">
							<label class="main-control d-flex align-items-center">
								<input type="radio"
									{{ request()->segment_uid ? '' : 'checked' }}
									name="which"
									value="whole_list" class="styled" /><rtitle>{{ trans('messages.export.whole_list') }} ({{ $list->readCache('SubscriberCount', 0) }} {{ strtolower(trans('messages.subscribers')) }})</rtitle>
							</label>
						</div>
						<div class="radio_box" data-popup='tooltip' title="">
							<label class="main-control d-flex align-items-center">
								<input type="radio"
									{{ request()->segment_uid ? 'checked' : '' }}
									{{ $list->segments()->count() ? '' : 'disabled' }}
									name="which"
									value="segment" class="styled" /><rtitle>{{ trans('messages.export.choose_segment') }}</rtitle>
							</label>
							<div class="radio_more_box pt-3">
								@include('helpers.form_control', [
									'value' => '',
									'type' => 'select',
									'name' => 'segment_uid[]',
									'label' => '',
									'value' => request()->segment_uid,
									'options' => $list->getSegmentSelectOptions(),
									'placeholder' => trans('messages.export.choose_segment'),
								])

							</div>
						</div>                        
					</div>

					<hr>
					<div class="text-left">
						<button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.export') }}</button>
					</div>
					<br />
				</div>
			</form>
		</div>
	</div>

@endsection
