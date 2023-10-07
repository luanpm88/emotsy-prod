<label>
    {{ trans('messages.email_subject') }} <span class="text-danger">*</span>
</label>
<div ab-control="ab-subjects" class="mt-2 bg-light border mb-3 rounded ab-subjects">
    <div class="form-group control-text mb-3 {{ $errors->has('subject') ? 'has-error' : '' }}">
        <div class="input-group">
            <span class="input-group-text text-center justify-content-center text-semibold ab-subjects-label" id="basic-addon1" style="width:42px">A</span>
            <span class="has-emoji d-block ab-subjects-input">
                <input id="subject"
                    type="text"
                    name="subject"
                    value="{{ $campaign->subject }}"
                    class="form-control required" data-emojiable="true"
                >
            </span>
        </div>
        @if ($errors->has('subject'))
            <span class="help-block">
                {{ $errors->first('subject') }}
            </span>
        @endif
    </div>

    <div class="form-group control-text mb-3 {{ $errors->has('subject_b') ? 'has-error' : '' }} ab-subjects-b-line">
        <div class="input-group">
            <span class="input-group-text text-center justify-content-center text-semibold" id="basic-addon1" style="width:42px">B</span>
            <span class="has-emoji d-block ab-subjects-input">
                <input id="subject_b"
                    type="text"
                    name="subject_b"
                    value="{{ $campaign->subject_b }}"
                    class="form-control required" data-emojiable="true"
                >
            </span>
        </div>
        @if ($errors->has('subject_b'))
            <span class="help-block">
                {{ $errors->first('subject_b') }}
            </span>
        @endif
    </div>

    <p class="mb-0 small ab-subjects-intro">{{ trans('messages.campaign.ab.email_subject.wording') }}</p>
</div>

<script>
    var aBVariableControl;

    $(function() {
        aBVariableControl = new ABVariableControl({
            subjectsBox: document.querySelector('[ab-control="ab-subjects"]'),
            splitSubjectChecker: document.querySelector('[ab-control="split-subject-checker"]'),
        });
    });

    var ABVariableControl = class {
        constructor(options) {
            this.options = options;

            this.toggleSplitSubjectOrNot();

            // events
            this.getSplitSubjectChecker().addEventListener('change', (e) => {
                this.toggleSplitSubjectOrNot();
            });
        }
        
        hideMutipleSubjects() {
            this.getSubjectBox().classList.remove('ab-subjects-no-split');
        }

        showMutipleSubjects() {
            this.getSubjectBox().classList.add('ab-subjects-no-split');
        }

        toggleSplitSubjectOrNot() {
            if (this.isSplitSubjectChecked()) {
                this.hideMutipleSubjects();
            } else {
                this.showMutipleSubjects();
            }
        }

        isSplitSubjectChecked() {
            return this.getSplitSubjectChecker().checked;
        }

        getSubjectBox() {
            return this.options.subjectsBox;
        }

        getSplitSubjectChecker() {
            return this.options.splitSubjectChecker;
        }
    }
</script>