@extends('layouts.core.backend', [
    'menu' => 'translation',
])

@section('title', trans('messages.translation.phrases'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">format_list_bulleted</span> {{ trans('messages.translation') }}</span>
        </h1>
    </div>

@endsection

@section('content')
    <form data-control="translation-form" method="POST" action="{{ action('Admin\TranslationController@phrases') }}">
        @csrf
        <div class="my-4">
            <div class="d-flex justify-content-end">
                <button data-control="refresh" type="button" class="btn btn-light">
                    {{ trans('messages.translation.refresh_load_new_phrases') }}
                </button>
                <button data-control="save" type="button" class="btn btn-secondary ms-2">
                    {{ trans('messages.translation.finish_write') }}
                </button>
            </div>
        </div>

        <table class="table table-striped">
            <thead>
                <th width="20%">{{ trans('messages.translation.key') }}</th>
                <th width="40%">{{ trans('messages.translation.original_english') }}</th>
                <th width="40%">{{ trans('messages.translation.japanese') }}</th>
            </thead>

            <tbody>
                @foreach ($phrases as $phrase)
                    <tr>
                        <td>
                            <code style="font-size:12px;">{{ $phrase->key }}</code>
                        </td>
                        <td>
                            {{ trans($phrase->key) }}
                        </td>
                        <td>
                            <input data-control="phrase-input" type="text" name="phrases[{{ $phrase->key }}]" value="{{ $phrase->ja }}" class="form-control" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>

    <script>
        $(function() {
            new TranslationManager($('[data-control="translation-form"]'));
        });

        var TranslationManager = class {
            constructor(form) {
                this.form = form;

                this.events();
            }

            getSaveUrl() {
                return this.form.attr('action');
            }

            getSaveButton() {
                return this.form.find('[data-control="save"]');
            }

            getRefreshButtonn() {
                return this.form.find('[data-control="refresh"]');
            }

            getInputs() {
                return this.form.find('[data-control="phrase-input"]');
            }

            events() {
                // submit
                this.form.on('submit', (e) => {
                    e.preventDefault();
                    this.save();
                });

                // click save
                this.getSaveButton().on('click', (e) => {
                    this.save();
                });

                // click refresh
                this.getRefreshButtonn().on('click', (e) => {
                    window.location.reload();
                });

                // input change
                this.getInputs().on('change', (e) => {
                    this.autoSave();
                });

                var inputFields = this.getInputs();
                inputFields.on('keydown', function(event) {
                    const currentInput = $(this);
                    const currentIndex = inputFields.index(currentInput);
                    
                    switch(event.key) {
                        case 'Enter':
                        case 'ArrowDown':
                        case 'Tab':
                            event.preventDefault(); // Prevent default tab behavior
                            if (currentIndex < inputFields.length - 1) {
                                inputFields.eq(currentIndex + 1).focus();
                            } else {
                                // If at the last input, move focus to the first input
                                inputFields.eq(0).focus();
                            }
                            break;
                        case 'ArrowUp':
                            if (currentIndex > 0) {
                                inputFields.eq(currentIndex - 1).focus();
                            } else {
                                // If at the first input, move focus to the last input
                                inputFields.eq(inputFields.length - 1).focus();
                            }
                            break;
                    }
                });
            }

            save() {
                addMaskLoading();

                $.ajax({
                    url: this.getSaveUrl(),
                    type: 'POST',
                    data: this.form.serialize(),
                }).done((response) => {
                    // notify
                    notify({
                        type: 'success',
                        message: response.message
                    });

                    removeMaskLoading();
                }).fail((response) => {
                });
            }

            autoSave() {
                $.ajax({
                    url: this.getSaveUrl(),
                    type: 'POST',
                    data: this.form.serialize(),
                }).done((response) => {
                    // notify
                    notify({
                        type: 'success',
                        message: '{{ trans('messages.translation.auto_aved') }}'
                    });
                }).fail((response) => {
                });
            }
        }
    </script>
@endsection
