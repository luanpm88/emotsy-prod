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
                            <input data-control="phrase-input" type="text" name="{{ $phrase->key }}" class="form-control" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>

    <script>
        $(function() {
            var translationManager = new TranslationManager($('[data-control="translation-form"]'));

            // change
            $('[data-control="phrase-input"]').on('change', function() {
                translationManager.autoSave();
            });
        });

        var TranslationManager = class {
            constructor(form) {
                this.form = form;

                this.events();
            }

            getSaveUrl() {
                return this.form.attr('action');
            }

            getSaveButtonn() {
                return this.form.find('[data-control="save"]');
            }

            getRefreshButtonn() {
                return this.form.find('[data-control="refresh"]');
            }

            events() {
                // click save
                this.getSaveButtonn().on('click', (e) => {
                    this.save();
                });

                // click refresh
                this.getRefreshButtonn().on('click', (e) => {
                    window.location.reload();
                });
            }

            save() {
                addMaskLoading();

                $.ajax({
                    url: this.getSaveUrl(),
                    type: 'POST',
                }).done((response) => {
                    console.log(response);

                    // notify
                    notify({
                        type: 'success',
                        message: response.message
                    });

                    removeMaskLoading();
                }).failed((response) => {
                });
            }

            autoSave() {
                $.ajax({
                    url: this.getSaveUrl(),
                    type: 'POST',
                }).done((response) => {
                    console.log(response);

                    // notify
                    notify({
                        type: 'success',
                        message: '{{ trans('messages.translation.auto_aved') }}'
                    });
                }).failed((response) => {
                });
            }
        }
    </script>
@endsection
