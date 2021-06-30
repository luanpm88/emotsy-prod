@extends('layouts.frontend')

@section('title', $list->name . ": " . trans('messages.import'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')

    @include("lists._header")

@endsection

@section('content')

    @include("lists._menu")

    <h2 class="text-bold text-teal-800"><i class="icon-users4"></i> {{ trans('messages.import_subscribers') }}</h2>

    <div class="row">
        <div class="col-md-8">
            <form id="importForm"
                action="{{ action('SubscriberController@import', $list->uid) }}" method="POST" class="ajax_upload_form form-validate-jquery">
                {{ csrf_field() }}

                <div class="alert alert-info">
                    {!! trans('messages.list.import.instruction', [
                        'csv_link' => url('files/csv_import_example.csv'),
                        'size' => \Acelle\Library\Tool::maxFileUploadInBytes()
                    ]) !!}
                </div>

                <div class="upload_file before">

                    @include('helpers.form_control', ['required' => true, 'type' => 'file', 'label' => trans('messages.upload_file'), 'name' => 'file', 'value' => $list->name])

                    @if (\Acelle\Model\Setting::get('import_subscribers_commitment'))
                        <div class="mt-40">
                            @include('helpers.form_control', [
                                'type' => 'checkbox2',
                                'class' => 'policy_commitment mb-10 required',
                                'name' => 'policy_commitment',
                                'value' => 'no',
                                'required' => true,
                                'label' => \Acelle\Model\Setting::get('import_subscribers_commitment'),
                                'options' => ['no','yes'],
                                'rules' => []
                            ])
                        </div>
                        <hr>
                    @endif

                    <div class="text-left">
                        <button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.import') }}</button>
                    </div>
                    <br />
                </div>

                <div class="form-group processing hide">
                    <h4>{{ trans('messages.please_wait_import') }}</h4>
                    <div class="progress progress-lg">
                        <!--<div class="progress-bar progress-success bg-success-400" style="width: 20%">
                            <span class="sr-only"><span class="number">20</span>% Complete</span>
                        </div>-->

                        <div class="progress-bar progress-error progress-bar-danger" style="width: 0%">
                            <span><span class="number">0</span>% {{ trans('messages.error') }}</span>
                        </div>

                        <div class="progress-bar progress-total active" style="width: 0%">
                            <span><span class="number">0</span>% {{ trans('messages.complete') }}</span>
                        </div>
                    </div>
                    <label></label>
                    <a id="cancelBtn" class="btn bg-grey btn-icon cancel processing">
                        {{ trans('messages.cancel') }}
                    </a>
                </div>

                <div class="form-group finish hide">
                    <div class="text-left">
                        <a target="_blank" href="{{ action('SubscriberController@downloadImportLog', ["list_uid" => $list->uid]) }}" type="button" class="btn bg-teal success">
                            <i class="icon-download"></i> {{ trans('messages.download_import_log') }}
                        </a>
                        <a href="#retry" class="btn bg-grey-800 mr-10 retry"><i class="icon-reload-alt"></i> {{ trans('messages.import_another') }}</a>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script>
        var AcelleImport = {
            
            // Current import job if any
            currentImportJobUid: null,
            progressCheckUrl: null,
            cancelUrl: null,

            progressCheck: null,

            // Update import progress
            updateProgressBar: function(percentage, message) { // percentage from 0 to 100
                var form = $("form.ajax_upload_form");
                var bar = form.find('.progress-total');

                form.find(".processing label").html(message);
                bar.find(".number").html(percentage);
                bar.css({
                    width: (percentage) + '%'
                });
            },

            // Toggle: show progress bar, hide input upload bar
            showProgressBar: function() {
                var form = $("form.ajax_upload_form");
                form.find('.before').addClass("hide");
                form.find(".processing").removeClass('hide');
            },

            hideProgressBar: function() {
                var form = $("form.ajax_upload_form");
                form.find('.before').removeClass("hide");
                form.find(".processing").addClass('hide');
            },

            showCancelButton: function() {
                var form = $("form.ajax_upload_form");
                form.find('.cancel').removeClass('hide');
            },

            hideCancelButton: function() {
                var form = $("form.ajax_upload_form");
                form.find('.cancel').addClass('hide');
            },

            checkProgress: function(completeAlert = true) {
            	var form = $("form.ajax_upload_form");
	            var bar = form.find('.progress-total');
	            var bar_s = form.find('.progress-success');
	            var bar_e = form.find('.progress-error');

	            $.ajax({
	                url : AcelleImport.progressCheckUrl,
	                type: "GET",
	                success: function(result, textStatus, jqXHR) {
	                    // Upgrade progress, no matter which status is
	                    AcelleImport.showProgressBar();
	                    AcelleImport.updateProgressBar(result.percentage, result.message);

	                    if (result.status == "failed") {
	                        AcelleImport.hideCancelButton();
	                        form.find('.finish').removeClass("hide");
	                        form.find('.success').addClass("hide");
	                    } else if (result.status == "done") {
	                        AcelleImport.hideCancelButton();
	                        form.find('.upload_file .progress-bar').addClass('success');
	                        form.find('.finish').removeClass('hide');
	                        form.find('.success').removeClass("hide");
	                        form.find(".processing h4").html('{{ trans('messages.import_completed') }}');

	                        if (completeAlert) {
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
	                    } else if (result.status == "cancelled") {
	                        /*
	                        AcelleImport.hideProgressBar();
	                        form.find('.finish').addClass("hide");
	                        form.find('.success').removeClass("hide");
	                        */
	                    } else if (result.status == "running" || result.status == "queued") {
	                        AcelleImport.showProgressBar();
	                        AcelleImport.progressCheck = setTimeout(function() {
	                        	 AcelleImport.checkProgress();
	                        }, 2000);
	                    }

	                    return false;
	                }
	            });
            },

            showFinishButtonBar: function() {
            	$(".finish").removeClass('hide');
            },

            hideFinishButtonBar: function() {
            	$(".finish").addClass('hide');
            },

            stopCheckingProgress: function() {
            	clearTimeout(AcelleImport.progressCheck);
            },

            upload: function() {
                var form = $("form.ajax_upload_form");

                if (!form.valid()) {
                    $("label.error").insertAfter(".uploader");
                    return false;
                }

                var formData = new FormData(form[0]); // Make the upload form and submit
                var url = form.attr('action');
                AcelleImport.showProgressBar();
                AcelleImport.updateProgressBar(0, "{{ trans('messages.uploading') }}");

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        // Set the JobID to query progress
                        // Upon receiving the response containing the job_id as well as progress_check_url
                        // Make another request to query progress
                        AcelleImport.currentImportJobUid = data.job_uid;
                        AcelleImport.progressCheckUrl = data.progress_check_url;
                        AcelleImport.cancelUrl = data.cancel_url;

                        AcelleImport.checkProgress();
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                    swal({
                        title: errorThrown,
                        text: "",
                        confirmButtonColor: "#00695C",
                        type: "error",
                        allowOutsideClick: true,
                        confirmButtonText: LANG_OK,
                    });
                    return;
                });

                return false;
            },

            importAnotherFile: function() {
            	AcelleImport.stopCheckingProgress();
            	var token = $('form#importForm').find('input[name="_token"]').val();

                $.ajax({
                    url : AcelleImport.cancelUrl,
                    type: "POST",
                    data: {
                    	'_token': token
                    },
                    success: function(result, textStatus, jqXHR) {
                        AcelleImport.hideFinishButtonBar();
                        AcelleImport.hideProgressBar();
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    swal({
                        title: errorThrown,
                        text: "",
                        confirmButtonColor: "#00695C",
                        type: "error",
                        allowOutsideClick: true,
                        confirmButtonText: LANG_OK,
                    });

                    // Resume progress checking
                    AcelleImport.checkProgress();
                });
            },

            cancel: function() {
            	AcelleImport.stopCheckingProgress();
            	var token = $('form#importForm').find('input[name="_token"]').val();

                $.ajax({
                    url : AcelleImport.cancelUrl,
                    type: "POST",
                    data: {
                    	'_token': token
                    },
                    success: function(result, textStatus, jqXHR) {
                        AcelleImport.hideFinishButtonBar();
                        AcelleImport.hideProgressBar();
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    swal({
                        title: errorThrown,
                        text: "",
                        confirmButtonColor: "#00695C",
                        type: "error",
                        allowOutsideClick: true,
                        confirmButtonText: LANG_OK,
                    });

                    // Resume progress checking
                    AcelleImport.checkProgress();

                    return false;
                });
            }
        }

        var listUID = '{{ $list->uid }}';

        $(document).on("submit", "form.ajax_upload_form", function() {
            AcelleImport.upload();
            return false; // avoid triggering the click action of <A>
        });

        $(document).on("click", ".retry", function() {
            AcelleImport.importAnotherFile();
            return false;
        });

        $(document).on("click", "#cancelBtn", function() {
            var cancelConfirm = confirm("{{ trans('messages.list.import.cancel') }}");

            if (cancelConfirm) {
                AcelleImport.cancel();
            }

            return false;
        });

        $(document).ready(function() {
            @if (isset($currentJobUid))
                AcelleImport.showProgressBar();
                AcelleImport.updateProgressBar(0, 'Checking...');

                AcelleImport.currentImportJobUid = '{{ $currentJobUid }}';
                AcelleImport.progressCheckUrl = '{{ $progressCheckUrl }}';
                AcelleImport.cancelUrl = '{{ $cancelUrl }}';

                AcelleImport.checkProgress(false);
            @endif
        });
    </script>
@endsection
