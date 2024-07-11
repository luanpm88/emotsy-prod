    <table class="table table-box table-box-head field-list">
        <thead>
            <th width="1%" class="ps-0"></th>
            <th width="20%" class="py-3 fw-600">{{ trans('messages.translation.key') }}</th>
            <th width="40%" class="py-3 fw-600">{{ trans('messages.translation.japanese') }}</th>
        </thead>

        <tbody>
            @foreach ($phrases as $phrase)
                <tr>
                    <td class="ps-0">
                        <label>
                            <input type="checkbox" class="node styled"
                                   name="uids[]"
                                   value="{{ $phrase->uid }}"
                            />
                        </label>
                    </td>
                    <td>
                        <code style="font-size:12px;" class="mb-3 text-muted2 text-dark">{{ $phrase->key }}</code>
                        <p class="mb-0">{{ trans($phrase->key) }}</p>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <input data-control="phrase-input" type="text" name="phrases[{{ $phrase->key }}]" value="{{ $phrase->ja }}"
                                placeholder="{{ trans('messages.translation.enter_phrase') }}"
                                class="inline-editable mode-view"
                            />
                            <div class="inine-actions ms-4 text-nowrap">
                                <button data-control="done-button" data-url="{{ action('Admin\TranslationController@phrasesWrite', [
                                    'uid' => $phrase->uid,
                                ]) }}" class="btn btn-secondary">{{ trans('messages.translation.done') }}</button>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @include('elements/_per_page_select', ["items" => $phrases])