<div class="form-group">
    <label for=""><strong>{{ trans('messages.campaign.ab.variables') }}</strong></label>
    <div>
        <div class="d-flex ps-2 ms-1">
            <div class="pt-1">
                <label class="checker">
                    <input type="hidden" name="ab_split_subject" value="0">
                    <input ab-control="split-subject-checker" {{ $campaign->ab_split_subject ? 'checked' : '' }} type="checkbox" name="ab_split_subject" value="1"
                        class="styled4">
                    <span class="checker-symbol"></span>
                </label>
            </div>
            <div class="ps-4">
                <label for="" class="mb-0 fw-semibold">{{ trans('messages.campaign.ab.subject_lines') }}</label>
                <p>{{ trans('messages.campaign.ab.split_subject.wording') }}</p>
            </div>
        </div>
        <div class="d-flex ps-2 ms-1">
            <div class="pt-1">
                <label class="checker">
                    <input type="hidden" name="ab_split_content" value="0">
                    <input {{ $campaign->ab_split_content ? 'checked' : '' }} type="checkbox" name="ab_split_content" value="1"
                        class="styled4">
                    <span class="checker-symbol"></span>
                </label>
            </div>
            <div class="ps-4">
                <label for="" class="mb-0 fw-semibold">{{ trans('messages.campaign.ab.split_email_content') }}</label>
                <p>{{ trans('messages.campaign.ab.split_email_content.wording') }}</p>
            </div>
        </div>
    </div>

    @if ($errors->has('ab_variables'))
        <div class="alert alert-danger">asdasd</div>
    @endif
</div>
