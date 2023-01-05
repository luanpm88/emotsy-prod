<div id="ImportProgressBar" class="content-group-sm mt-20">
    <div class="d-flex">
        <span class="text-muted progress-xxs me-auto">
            <span progress-control="processed">{{ $progress['processed'] }}</span> /
            <span progress-control="total">{{ $progress['total'] }}</span>
        </span>
        <span><span progress-control="percentage">{{ $progress['percentage'] }}</span>%</span>
    </div>
    <div class="progress mt-2">
        <div progress-control="percentage-bar" class="progress-bar progress-bar-striped bg-info" style="width: {{ $progress['percentage'] }}%">
        </div>
    </div>
    <div class="mt-1">
        <span class="text-semibold mb-5 mt-0 mb-2 d-block text-muted fst-italic" progress-control="message">{{ $progress['message'] }}</span>
    </div>
    <div class="mt-4">
        @if ($progress['percentage'] < 100)
            
        @else
            <a id="ImportFinishButton" href="javascript:;" class="btn btn-secondary me-1" progress-control="cancel">
                {{ trans('messages.finish') }}
            </a>
            <a href="{{ action('SubscriberController@downloadImportLog', ['job_uid' => $currentJob->uid]) }}" class="btn btn-default" progress-control="cancel">
                {{ trans('messages.download_import_log') }}
            </a>
        @endif
    </div>
</div>

<script>
    $(function() {
        // cancel import
        $('#ImportCancelButton').on('mousedown', function() {
            var cancelConfirm = confirm("{{ trans('messages.list.import.cancel') }}");

            if (cancelConfirm) {
                SubscribersImportProgress.cancelImport();
            }
        });

        // cancel import
        $('#ImportFinishButton').on('click', function() {
            SubscribersImportProgress.cancelImport();
        });

        @if ($progress['percentage'] < 100)
            setTimeout(function() {
                SubscribersImportProgress.checkProgress();
            }, 1000);

            $('#ImportCancelButton').show();
            $('#ImportCloseButton').show();
        @else
            $('#ImportCancelButton').hide();
            $('#ImportCloseButton').hide();
        @endif
    });
</script>