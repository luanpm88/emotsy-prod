@if ($sites->count() > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($sites as $key => $site)
            <tr class="position-relative">
                <td width="1%" class="list-check-col">
                    <div class="text-nowrap">
                        <div class="checkbox inline me-1">
                            <label>
                                <input type="checkbox" class="node styled"
                                    name="uids[]"
                                    value="{{ $site->uid }}"
                                />
                            </label>
                        </div>
                    </div>
                </td>
                <td width="1%">
                    <a href="#preview"  onclick="popupwindow('{{ action('SiteController@preview', $site->uid) }}', `{{ $site->name }}`, 800)">
                        <img class="template-thumb rounded shadow-sm" width="100" height="128" src="{{ $site->getThumbUrl() }}?v={{ rand(0,10) }}" />
                    </a>
                </td>
                <td width="50%">
                    <h5 class="m-1 text-bold">
                        <a class="kq_search d-block" href="{{ action('SiteController@edit', $site->uid) }}">{{ $site->name }}</a>
                    </h5>
                    <span class="d-block">{{ trans('messages.site.landing_page') }}</span>
                    <span class="text-muted">{{ trans('messages.created_at') }}: {{ Auth::user()->customer->formatDateTime($site->created_at, 'datetime_full') }}</span>
                </td>
                <td>
                    <span class="text-muted2 list-status pull-left">
                        <span class="label label-flat bg-{{ $site->status }}">{{ trans('messages.site.status.' . $site->status) }}</span>
                    </span>
                </td>
                <td>
                    @if ($site->is_default)
                        <span class="text-muted2 list-status pull-left">
                            <span class="label label-flat bg-active">{{ trans('messages.site.default') }}</span>
                        </span>
                    @endif
                </td>
                <td class="text-end text-nowrap pe-0">
                    @if (Auth::user()->customer->can('update', $site))
                        <a href="{{ action('SiteController@content', $site->uid) }}" title="{{ trans('messages.edit') }}" role="button" class="btn btn-secondary btn-icon">
                            <span class="material-symbols-rounded">edit</span>
                            {{ trans('messages.edit') }}
                        </a>
                    @endif
                    @if (Auth::user()->customer->can('delete', $site) || Auth::user()->customer->can('disable', $site) || Auth::user()->customer->can('enable', $site))
                        <div class="btn-group">
                            <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (Auth::user()->customer->can('enable', $site))
                                    <li>
                                        <a class="dropdown-item list-action-single" link-method="POST" link-confirm="{{ trans('messages.site.enable.confirm') }}" href="{{ action('SiteController@enable', ["uids" => $site->uid]) }}">
                                            <span class="material-symbols-rounded">play_arrow</span> {{ trans('messages.enable') }}
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->customer->can('disable', $site))
                                    <li>
                                        <a class="dropdown-item list-action-single" link-method="POST" link-confirm="{{ trans('messages.site.enable.confirm') }}" href="{{ action('SiteController@disable', ["uids" => $site->uid]) }}">
                                            <span class="material-symbols-rounded">hide_source</span> {{ trans('messages.disable') }}
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->customer->can('delete', $site))
                                    <li>
                                        <a class="dropdown-item list-action-single" link-method="POST" link-confirm="{{ trans('messages.site.delete.confirm') }}" href="{{ action('SiteController@delete', ["uids" => $site->uid]) }}">
                                            <span class="material-symbols-rounded">delete_outline</span> {{ trans('messages.delete') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
    @include('elements/_per_page_select', [
        'items' => $sites,
    ])
    
@elseif (!empty(request()->keyword) || !empty(request()->filters["type"]))
    <div class="empty-list">
        <span class="material-symbols-rounded">dns</span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <span class="material-symbols-rounded">dns</span>
        <span class="line-1">
            {{ trans('messages.site.empty') }}
        </span>
    </div>
@endif
