@extends('layouts.frontend')

@section('title', $list->name . ": " . trans('messages.import'))

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
				
				<h2 class="text-bold text-teal-800"><i class="icon-users4"></i> {{ trans('messages.import_subscribers') }}</h2>
				
                <div class="row">
                    <div class="col-md-12">
                        <form
                            process-url="{{ action('SubscriberController@importProccess', $list->uid) }}"
                            action="{{ action('SubscriberController@import', $list->uid) }}" method="POST" class="ajax_upload_form form-validate-jquery">
                            {{ csrf_field() }}
                            
							<div class="alert alert-info">
								{!! trans('messages.import_file_help', ['csv_link' => url('files/csv_import_example.csv')]) !!}
							</div>
							
							
                            <div class="upload_file before">
                                {!! trans('messages.max_file_upload', ['size' => \Acelle\Library\Tool::maxFileUploadInBytes()]) !!}
								
                                @include('helpers.form_control', ['required' => true, 'type' => 'file', 'label' => trans('messages.upload_file'), 'name' => 'file', 'value' => $list->name])								
                                
                                <div class="text-right">
                                    <button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.import') }}</button>
                                    <a href="{{ action('SubscriberController@index', $list->uid) }}" class="btn bg-grey-800"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
                                </div>
                                <br />
                            </div>
                            
                            <div class="form-group processing hide">
								<h4>{{ trans('messages.please_wait_import') }}</h4>
                                <div class="progress progress-lg">
									<!--<div class="progress-bar progress-success bg-success-400" style="width: 20%">
										<span class="sr-only"><span class="number">20</span>% Complete</span>
									</div>-->

									<div class="progress-bar progress-error progress-bar-danger" style="width: 30%">
										<span><span class="number">30</span>% {{ trans('messages.error') }}</span>
									</div>

                                    <div class="progress-bar progress-total active" style="width: 0%">
                                        <span><span class="number">0</span>% {{ trans('messages.complete') }}</span>
                                    </div>
                                </div>
								<label></label>
                            </div>
								
							<div class="form-group finish hide">
                                <div class="text-right">
									<a target="_blank" href="{{ action('SubscriberController@downloadImportLog', ["list_uid" => $list->uid]) }}" type="button" class="btn bg-teal finish hide">
                                        <i class="icon-download"></i> {{ trans('messages.download_import_log') }}
                                    </a>
                                    <a href="#retry" class="btn bg-grey-800 mr-10 import_another"><i class="icon-reload-alt"></i> {{ trans('messages.import_another') }}</a>
                                </div>
                            </div>
                            
                        </form>  
                        
                    </div>
                </div>
                      
                <script>
						function check_process() {
							var form = $("form.ajax_upload_form")
							var url = form.attr("process-url")
							var bar = form.find('.progress-total');
							var bar_s = form.find('.progress-success');
							var bar_e = form.find('.progress-error');
							
                            $.ajax({
								url : url + "?current_job_id="+current_job_id,
								type: "GET",
								success:function(data, textStatus, jqXHR)
								{
									// update progress bar
									var total = parseFloat($("<div>").html(data.message).find(".total").html());
									var success = parseFloat($("<div>").html(data.message).find(".success").html());
									var error = parseFloat($("<div>").html(data.message).find(".error").html());
								
									form.find(".processing label").html(data.message);
									bar.find(".number").html(Math.round(success/total*100));
									bar.css({
										width: (success/total*100) + '%'
									});

									bar_e.find(".number").html(Math.round(error/total*100));
									bar_e.css({
										width: (error/total*100) + '%'
									});									
									
									if (data.status == "running" || data.status == 'starting') {
                                        setTimeout("check_process()", 500);
                                    }
									if (data.status == "error" ) {
										form.find('.finish').removeClass("hide");
										form.find('.before').addClass("hide");
                                    }
									if (data.status == "finished") {
										if(!form.find('.finish').hasClass('hide')) {
											// Success alert
											swal({
												title: '{{ trans('messages.import_completed') }}',
												text: "",
												confirmButtonColor: "#00695C",
												type: "success",
												allowOutsideClick: true,
												confirmButtonText: LANG_OK,
											});
										}
										form.find('.upload_file .progress-bar').addClass('success');
										form.find('.finish').removeClass('hide');
										form.find(".processing h4").html('{{ trans('messages.import_completed') }}');
										
									}
								}
							})
                        }						
						
						var current_job_id;
						$(document).on("submit", "form.ajax_upload_form", function() {                
							var form = $(this);
                            setTimeout("check_process()", 2000);
                            if(!form.valid()) {
                                $("label.error").insertAfter(".uploader");
                                return false;
                            }
                            
							var formData = new FormData($(this)[0]);
							var url = form.attr('action');
							var bar = form.find('.progress-total');
							var bar_s = form.find('.progress-success');
							var bar_e = form.find('.progress-error');
							
							// return to 0
							bar_s.find(".number").html(parseInt(0));
							bar_s.css({
								width: 0 + '%'
							});
							bar_e.find(".number").html(parseInt(0));
							bar_e.css({
								width: 0 + '%'
							});
							
							form.find(".processing h4").html('{{ trans('messages.please_wait_import') }}');
							
                            $(".processing label").html("{{ trans('messages.uploading') }}");
                            $(".before").addClass('hide');
                            $(".processing").removeClass('hide');
                            
							$.ajax({
								xhr: function () {
									var xhr = new window.XMLHttpRequest();
									xhr.upload.addEventListener("progress", function (evt) {
										if (evt.lengthComputable) {
											var percentComplete = evt.loaded / evt.total;
											console.log(percentComplete);
											bar.find(".number").html(parseInt(percentComplete * 100));
											bar.css({
												width: percentComplete * 100 + '%'
											});
										}
									}, false);
									xhr.addEventListener("progress", function (evt) {
										if (evt.lengthComputable) {
											var percentComplete = evt.loaded / evt.total;
											console.log(percentComplete);
											bar.find(".number").html(parseInt(percentComplete * 100));
											bar.css({
												width: percentComplete * 100 + '%'
											});
										}
									}, false);
									return xhr;
								},
								url: url,
								type: 'POST',
								data: formData,
								success: function (data) {
									current_job_id = data;
                                    check_process();
									
									if(data == 'max_file_upload') {
										// Success alert
										swal({
											title: '{{ trans('messages.file_import_to_large') }}',
											text: "",
											confirmButtonColor: "#00695C",
											type: "error",
											allowOutsideClick: true,
											confirmButtonText: LANG_OK,
										});
										
										form.find('.import_another').trigger('click');
										return;
									}
								},
								cache: false,
								contentType: false,
								processData: false
							});
							
							return false;
						});
						$(document).on("click", ".import_another", function() {
							$(".input[type=file]").val("");
							$(".finish").addClass('hide');
                            $(".processing").addClass('hide');
							$(".before").removeClass('hide');
							var bars = $(".progress-bar");
							bars.find(".number").html(parseInt(0));
							bars.css({
								width: 0 + '%'
							});
						});
					</script>
						
						
			<hr>
			<h2 class="text-bold text-teal-800"><i class="icon-task"></i> {{ trans('messages.recent_import') }}</h2>
			<form class="listing-form"
					data-url="{{ action('SystemJobController@listing', ["type" => "import-subscriber"]) }}"
					per-page="{{ Acelle\Model\SystemJob::$itemsPerPage }}"					
				>				
					<div class="row top-list-controls">
						<div class="col-md-10">
							@if ($system_jobs->count() >= 0)					
								<div class="filter-box">
									<!--<div class="btn-group list_actions hide">
										<button type="button" class="btn btn-xs btn-grey-600 dropdown-toggle" data-toggle="dropdown">
											{{ trans('messages.actions') }} <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">											
											<li><a delete-confirm="{{ trans('messages.delete_system_jobs_confirm') }}" href="{{ action('SystemJobController@delete') }}"><i class="icon-trash"></i> {{ trans('messages.delete') }}</a></li>
										</ul>
									</div>
									<div class="checkbox inline check_all_list">
										<label>
											<input type="checkbox" class="styled check_all">
										</label>
									</div>-->
									<span class="filter-group">
										<span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
										<select class="select" name="sort-order">
											<option value="system_jobs.created_at">{{ trans('messages.created_at') }}</option>
											<option value="system_jobs.updated_at">{{ trans('messages.updated_at') }}</option>
										</select>										
										<button class="btn btn-xs sort-direction" rel="desc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" type="button" class="btn btn-xs">
											<i class="icon-sort-amount-desc"></i>
										</button>
									</span>									
								</div>
							@endif
						</div>
						<!--<div class="col-md-2 text-right">
							<a href="{{ action("SegmentController@create", $list->uid) }}" type="button" class="btn bg-info-800">
								<i class="icon icon-plus2"></i> {{ trans('messages.clear_all') }}
							</a>
						</div>-->
					</div>
					
					<div class="pml-table-container">
						
						
						
					</div>
				</form>
					
				<script>
					$(document).ready(function() {
						setInterval('tableFilterAll()', 3000);
					});
				</script>
@endsection
