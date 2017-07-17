<p>{!! trans('messages.blacklist.import_process_' . $system_job->status, [
    'processed' => $system_job->getData('processed'),
    'total' => $system_job->getData('total'),
]) !!}</p>

@include('helpers._progress_bar', [
    'percent' => $system_job->getData('total') == 0 ? 0 : $system_job->getData('processed')/$system_job->getData('total')
])

@if ($system_job->status == \Acelle\Model\SystemJob::STATUS_DONE)
    <h5 class="text-semibold">{{ trans('messages.blacklist.import.import_result') }}</h5>
    <div class="row">
        <div class="col-md-6">
            <ul class="dotted-list topborder section">
                <li>
                    <div class="unit size1of2">
                        <span>{{ trans('messages.blacklist.import.processed') }}</span>
                    </div>
                    <div class="lastUnit size1of2">
                        <mc:flag class="text-semibold">{{ $system_job->getData('processed') }}</mc:flag>
                    </div>
                </li>
                <li>
                    <div class="unit size1of2">
                        <span>{{ trans('messages.blacklist.import.success') }}</span>
                    </div>
                    <div class="lastUnit size1of2">
                        <mc:flag class="text-success text-semibold">{{ $system_job->getData('success') }}</mc:flag>
                    </div>
                </li>
                <li>
                    <div class="unit size1of2">
                        <span>{{ trans('messages.blacklist.import.failed') }}</span>
                    </div>
                    <div class="lastUnit size1of2">
                        <mc:flag class="text-danger text-semibold">{{ $system_job->getData('processed') - $system_job->getData('success') }}</mc:flag>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <br>
    <a href="{{ action('Admin\BlacklistController@import') }}" class="btn btn-primary bg-teal mr-5">
        <i class="icon-reload-alt"></i> {{ trans('messages.blacklist.import_another') }}
    </a>
    <a href="{{ action('Admin\BlacklistController@index') }}" class="btn btn-primary bg-grey">
        <i class="icon-arrow-left7"></i> {{ trans('messages.return_to_blacklist') }}
    </a>
@endif
