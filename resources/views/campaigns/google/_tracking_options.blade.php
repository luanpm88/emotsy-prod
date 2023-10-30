<div class="mb-5">
    <div class="d-flex mb-3">
        <div style="width:100%">
            <label class="me-5 mb-0">
                {{ trans('messages.camapign.google_analytic_link_tracking') }}
                <span class="checkbox-description mt-1">
                    {{ trans('messages.camapign.google_analytic_link_tracking.wording') }}
                </span>
            </label>
        </div>
        <div class="d-flex align-items-top">
            <label class="checker">
                <input type="hidden" name="google_analytic_link_tracking" value="0">
                <input ga-control="checker" {{ $campaign->google_analytic_link_tracking ? 'checked' : '' }} type="checkbox" name="google_analytic_link_tracking" value="1" class="styled4">
                <span class="checker-symbol"></span>
            </label>
        </div>
    </div>
    <div ga-control="connection-box">
        @if(Auth::user()->customer->getGoogleAccessToken())
            <div class="alert alert-success">{{ trans('messages.google.connected_to_google_analytics') }}</div>
            <div>
                <a href="{{ action('GoogleAnalyticsController@redirectToGoogle', [
                    'redirect' => action('CampaignController@setup', $campaign->uid)
                ]) }}" class="btn btn-default">{{ trans('messages.google-analytics.reconnect') }}</a>
            </div>
        @else
            <div>
                <a href="{{ action('GoogleAnalyticsController@redirectToGoogle', [
                    'redirect' => action('CampaignController@setup', $campaign->uid)
                ]) }}" class="btn btn-primary">{{ trans('messages.google-analytics.connect') }}</a>
            </div>
        @endif

        {{ $campaign->generateUtmLink('https://acellemail.com') }}

        <div ga-control="more-options" class="mt-3">
            <a  href="javascript:;" ga-control="show-options" href="">{{ trans('messages.google-analytics.more_options') }}</a>
            <div ga-control="options-box" style="display:none">
                <div>
                    <h5>{{ trans('messages.google-analytics.utm_parameters') }}</h5>
                    <table class="table">
                        <tr>
                            <td class="ps-0 small fw-semibold">Tracking?</td>
                            <td class="small fw-semibold">Parameter</td>
                            <td class="small fw-semibold">Value</td>
                        </tr>
                        <tr ga-control="option-row">
                            <td valign="middle" width="1%" class="ps-3">
                                <label class="checker mb-0">
                                    <input type="hidden" name="enable_utm_campaign" value="0">
                                    <input ga-control="option-checker" checked="" type="checkbox" name="enable_utm_campaign" value="1" class="styled4">
                                    <span class="checker-symbol"></span>
                                </label>
                            </td>
                            <td valign="middle">
                                Utm_campaign
                            </td>
                            <td valign="middle">
                                <div>
                                    <input data-default="{{ $campaign->getDefaultUtmParameters()['utm_campaign'] }}"
                                        ga-control="option-input" type="text"
                                        name="utm_campaign" value="{{ $campaign->getUtmParameters()['utm_campaign'] }}"
                                        class="form-control"
                                    >
                                </div>
                            </td>
                        </tr>
                        <tr ga-control="option-row">
                            <td valign="middle" width="1%" class="ps-3">
                                <label class="checker mb-0">
                                    <input type="hidden" name="enable_utm_campaign" value="0">
                                    <input
                                        ga-control="option-checker" checked="" type="checkbox"
                                        name="enable_utm_campaign" value="1" class="styled4"
                                    >
                                    <span class="checker-symbol"></span>
                                </label>
                            </td>
                            <td valign="middle">
                                Utm_meidum
                            </td>
                            <td valign="middle">
                                <div>
                                    <input data-default="{{ $campaign->getDefaultUtmParameters()['utm_medium'] }}"
                                        ga-control="option-input" type="text"
                                        name="utm_medium" value="{{ $campaign->getUtmParameters()['utm_medium'] }}" class="form-control"
                                    >
                                </div>
                            </td>
                        </tr>
                        <tr ga-control="option-row">
                            <td valign="middle" width="1%" class="ps-3">
                                <label class="checker mb-0">
                                    <input type="hidden" name="enable_utm_campaign" value="0">
                                    <input ga-control="option-checker" checked="" type="checkbox" name="enable_utm_campaign" value="1" class="styled4">
                                    <span class="checker-symbol"></span>
                                </label>
                            </td>
                            <td valign="middle">
                                Utm_source
                            </td>
                            <td valign="middle">
                                <div>
                                    <input data-default="{{ $campaign->getDefaultUtmParameters()['utm_source'] }}"
                                        ga-control="option-input" type="text"
                                        name="utm_source" value="{{ $campaign->getUtmParameters()['utm_source'] }}" class="form-control"
                                    >
                                </div>
                            </td>
                        </tr>
                        <tr ga-control="option-row">
                            <td valign="middle" width="1%" class="ps-3">
                                <label class="checker mb-0">
                                    <input type="hidden" name="enable_utm_campaign" value="0">
                                    <input ga-control="option-checker" checked="" type="checkbox" name="enable_utm_campaign" value="1" class="styled4">
                                    <span class="checker-symbol"></span>
                                </label>
                            </td>
                            <td valign="middle">
                                Utm_term
                            </td>
                            <td valign="middle">
                                <div>
                                    <input data-default="{{ $campaign->getDefaultUtmParameters()['utm_term'] }}"
                                        ga-control="option-input" type="text"
                                        name="utm_term" value="{{ $campaign->getUtmParameters()['utm_term'] }}" class="form-control"
                                    >
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        new GoogleAnalyticsTracking({
            checkbox: $('[ga-control="checker"]'),
            connectionBox: $('[ga-control="connection-box"]'),
            optionsBox: $('[ga-control="options-box"]'),
            showOptionsLink: $('[ga-control="show-options"]'),
            optionRows: $('[ga-control="option-row"]'),
        });
    });

    var GoogleAnalyticsTracking = class {
        constructor(options) {
            this.checkbox = options.checkbox;
            this.connectionBox = options.connectionBox;
            this.optionsBox = options.optionsBox;
            this.showOptionsLink = options.showOptionsLink;
            this.optionRows = options.optionRows;

            // toggle
            this.toggle();

            // events
            this.checkbox.on('click', (e) => {
                this.toggle();
            });

            this.showOptionsLink.on('click', (e) => {
                this.showOptionsBox();
            });

            // add options
            this.addOptions();
            
        }

        addOptions() {
            console.log(this.optionRows);
            this.optionRows.each(function() {
                var checker = $(this).find('[ga-control="option-checker"]');
                var inputBox = $(this).find('[ga-control="option-input"]');
                var defaultValue = inputBox.attr('data-default');
                
                var toogle = function() {
                    if (checker.is(':checked')) {
                        inputBox.prop('readonly', false);
                    } else {
                        inputBox.val(defaultValue);
                        inputBox.prop('readonly', true);
                    }
                }

                // event
                checker.on('change', function() {
                    toogle();
                });
            });
        }

        showOptionsBox() {
            this.optionsBox.show();
            this.showOptionsLink.hide();
        }

        toggle() {
            if (this.checkbox.is(':checked')) {
                this.showConnectionBox();
            } else {
                this.hideConnectionBox();
            }
        }

        showConnectionBox() {
            this.connectionBox.show();
        }

        hideConnectionBox() {
            this.connectionBox.hide();
        }
    }
</script>