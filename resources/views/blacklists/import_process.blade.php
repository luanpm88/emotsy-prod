<p>{!! trans('messages.blacklist.import_process_' . $system_job->status, [
    'processed' => $system_job->getData('processed'),
    'total' => $system_job->getData('total'),
]) !!}</p>

@include('helpers._progress_bar', [
    'percent' => $system_job->getData('total') == 0 ? 0 : $system_job->getData('processed')/$system_job->getData('total')
])
