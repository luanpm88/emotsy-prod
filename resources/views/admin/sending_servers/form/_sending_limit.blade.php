Maximum 
<span class="mc-text-bold value-text modal-trigger" data-type="number">
    <val>{{ Acelle\Library\Tool::format_number($quotaValue) }}</val>
    <input type="hidden" name="quota_value" value="{{ $quotaValue }}" />
</span>
email(s) per
<span class="mc-text-bold value-text modal-trigger" data-type="number">
    <val>{{ Acelle\Library\Tool::format_number($quotaBase) }}</val>
    <input type="hidden" name="quota_base" value="{{ $quotaBase }}" />
</span>
<span class="mc-text-bold value-text modal-trigger"><val>{{ trans('messages.' . $quotaUnit) }}</val><input type="hidden" name="quota_unit" value="{{ $quotaUnit }}" /></span>(s)

<span class="icon icon-pencil ml-20 modal-trigger"></span>