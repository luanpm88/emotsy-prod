<div id="SignatureSelectBox" class="sub-section">   
    <h2 class="mt-0 mb-3">{{ trans('messages.signature.select_signature') }}</h2>
    <p>{{ trans('messages.signature.tempate.intro') }}</p>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group mb-0">
                <select data-control="signature-selector" name="signature_uid" class="select">
                    <option value="">{{ trans('messages.signature.dont_insert_signature') }}</option>
                    @foreach (Auth::user()->customer->signatures()->active()->get() as $signature)
                        <option value="{{ $signature->uid }}">{{ $signature->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-8">
            <button data-control="signature-refresh" type="button" class="btn btn-secondary">
                <span class="material-symbols-rounded">refresh</span>
                {{ trans('messages.signature.refresh') }}
            </button>

            @if (!Auth::user()->customer->signatures()->count())
                <a href="{{ action('SignatureController@create') }}" target="_blank" class="btn btn-primary">
                    <span class="material-symbols-rounded">add</span>
                    {{ trans('messages.signature.add_new') }}
                </a>
            @else
                <a href="{{ action('SignatureController@index') }}" target="_blank" class="btn btn-light">
                    {{ trans('messages.signature.manage_signatures') }}
                    <span class="material-symbols-rounded">open_in_new</span>
                </a>
            @endif

            <span data-control="loader" class="spinner-border spinner-border-sm ms-3" style="display: none;" aria-hidden="true"></span>
        </div>
    </div>
</div>

<script>
    $(() => {
        new SignatureSelectBox({
            container: $('#SignatureSelectBox'),
            url: '{{ action('SignatureController@selectBox') }}',
        });
    })

    var SignatureSelectBox = class {
        constructor(options) {
            this.container = options.container;
            this.url = options.url;

            this.events();
        }

        getSelector() {
            return this.container.find('[data-control="signature-selector"]');
        }

        getRefreshButton() {
            return this.container.find('[data-control="signature-refresh"]');
        }

        getLoader() {
            return this.container.find('[data-control="loader"]');
        }

        events() {
            this.getRefreshButton().on('click', (e) => {
                e.preventDefault();

                this.refresh();
            });
        }

        refresh() {
            //
            this.getLoader().show();

            //
            this.getRefreshButton().prop('disabled', true);

            $.ajax({
                url: this.url,
            }).done((res) => {
                this.container.html($(res).html());
                initJs(this.container);

                // events
                this.events();
            });
        }
    }
</script>