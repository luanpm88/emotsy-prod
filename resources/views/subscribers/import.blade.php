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
                    <div class="col-md-8">
                        <form
                            process-url="{{ action('SubscriberController@import_proccess', $list->uid) }}"
                            action="{{ action('SubscriberController@import', $list->uid) }}" method="POST" class="ajax_upload_form form-validate-jquery">
                            {{ csrf_field() }}
                            
                            <div class="upload_file before">
                                <div class="alert alert-info">
                                    {!! trans('messages.import_file_help', ['link' => '']) !!}
                                </div>
                                    
                                @include('helpers.form_control', ['required' => true, 'type' => 'file', 'label' => trans('messages.upload_file'), 'name' => 'file', 'value' => $list->name])
                                
                                <div class="text-right">
                                    <button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.import') }}</button>
                                    <a href="{{ action('SubscriberController@index', $list->uid) }}" class="btn bg-grey-800"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
                                </div>
                                
                            </div>
                            <br />
                            <div class="form-group proccessing hide">
                                <label></label>
                                <div class="progress progress-lg">
                                    <div class="progress-bar progress-bar-striped active" style="width: 0%">
                                        <span><span class="number">0</span>% {{ trans('messages.complete') }}</span>
                                    </div>
                                </div>
                            </div>
								
							<div class="form-group finish hide">
                                <div class="text-right">
                                    <a href="#retry" class="btn bg-teal mr-10 import_another"><i class="icon-reload-alt"></i> {{ trans('messages.import_another') }}</a>
                                </div>
                            </div>
                            
                        </form>  
                        
                    </div>
                </div>
                      
                <script>
						function check_process() {
							var form = $("form.ajax_upload_form")
							var url = form.attr("process-url")
                            $.ajax({
								url : url,
								type: "GET",
								success:function(data, textStatus, jqXHR)
								{
									if (data[0] != "") {
										form.find(".proccessing label").html(data[1]);
										form.find(".number").html(parseInt(data[2]));
										form.find('.progress-bar').css({
											width: data[2] + '%'
										});
                                    }
									if (data[0] == "processing" || data[0] == 'start') {
                                        setTimeout("check_process()", 500);
                                    }
									if (data[0] == "error" ) {
										form.find('.finish').removeClass("hide");
										form.find('.before').addClass("hide");
                                    }
									if (data[0] == "finished") {
										form.find('.upload_file .progress-bar').addClass('success');
										form.find('.finish').removeClass('hide');
									}
								}
							})
                        }						
						
						$(document).on("submit", "form.ajax_upload_form", function() {                
							var form = $(this);
                            setTimeout("check_process()",2000);
                            if(!form.valid()) {
                                $("label.error").insertAfter(".uploader");
                                return false;
                            }
                            
							var formData = new FormData($(this)[0]);
							var url = form.attr('action');
							var bar = form.find('.progress-bar');
							
                            $(".proccessing label").html("{{ trans('messages.uploading') }}");
                            $(".before").addClass('hide');
                            $(".proccessing").removeClass('hide');
                            
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
                                    check_process();
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
                            $(".proccessing").addClass('hide');
							$(".before").removeClass('hide');
						});
					</script>
@endsection
