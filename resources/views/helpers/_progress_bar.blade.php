<div class="progress progress-lg mb-10">
    <div class="progress-bar progress-total active" style="width: {!! number_to_percentage($percent) !!}">
        <span><span class="number">{!! number_to_percentage($percent, 0) !!}</span> {{ trans('messages.complete') }}</span>
    </div>
</div>
