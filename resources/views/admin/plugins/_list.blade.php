@if ($plugins->count() > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($plugins as $key => $plugin)
            <tr>
                <td class="plugin-title-column plugin-title-{{ $plugin->name }}" >
                    <img class="plugin-icon" src="{{ $plugin->getIconUrl() ? $plugin->getIconUrl() : url('/images/plugin.svg') }}" />
                    <h5 class="no-margin text-bold kq_search">
                        {{ $plugin->title }}
                    </h5>
                    <span class="">
                        {{ $plugin->description }}
                    </span>
                    <br />
                    <span class="text-muted">{{ trans('messages.plugin.version') }}: {{ $plugin->version }}</span>
                </td>
                <td>
                    @if (array_key_exists($plugin->name, $blacklist))
                        <span style="cursor:pointer" class="text-muted2 list-status pull-left">
                            <span title="{{ $blacklist[$plugin->name] }}" class="xtooltip tooltipstered label label-flat bg-risky">
                                error
                            </span>
                        </span>
                    @else
                        <span class="text-muted2 list-status pull-left">
                            <span class="label label-flat bg-{{ $plugin->status }}">
                                {{ trans('messages.email_verification_server_status_' . $plugin->status) }}
                            </span>
                        </span>
                    @endif
                </td>
                <td class="text-end text-nowrap">
                    @if (isset($settingUrls[$plugin->name]))
                        <a
                            href="{{ $settingUrls[$plugin->name] }}"
                            class="btn btn-primary"
                        >
                            {{ trans('messages.setting') }}
                        </a>
                    @endif

                    @if (Auth::user()->admin->can('enable', $plugin))
                        <a link-confirm="{{ trans('messages.enable_plugins_confirm') }}"
                            href="{{ action('Admin\PluginController@enable', ["uids" => $plugin->uid]) }}"
                            class="btn btn-secondary list-action-single"
                        >
                            {{ trans('messages.enable') }}
                        </a>
                    @endif

                    @if (Auth::user()->admin->can('disable', $plugin))
                        <a link-confirm="{{ trans('messages.disable_plugins_confirm') }}"
                            href="{{ action('Admin\PluginController@disable', ["uids" => $plugin->uid]) }}"
                            class="btn btn-secondary list-action-single"
                        >
                            {{ trans('messages.disable') }}
                        </a>
                    @endif

                    <div class="btn-group">
						<button role="button" class="btn btn-light icon-only dropdown-toggle" data-bs-toggle="dropdown">
                            
                        </button>
						<ul class="dropdown-menu dropdown-menu-end">
							<li>
								<a
                                    class="dropdown-item list-action-single"
                                    link-confirm="{{ trans('messages.delete_plugins_confirm') }}"
                                    href="{{ action('Admin\PluginController@delete', ["uids" => $plugin->uid]) }}"
                                    role="button">
                                    <i class="material-icons-outlined">delete</i> {{ trans('messages.uninstall') }}
                                </a>
							</li>
						</ul>
					</div>
                </td>
            </tr>
        @endforeach
    </table>
    @include('elements/_per_page_select', [
        'items' => $plugins
    ])
    
@elseif (!empty(request()->keyword) || !empty(request()->filters["type"]))
    <div class="empty-list">
        <span class="material-icons-outlined">
            extension
            </span>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <span class="material-icons-outlined">
            extension
            </span>
        <span class="line-1">
            {{ trans('messages.plugin_empty_line_1') }}
        </span>
    </div>
@endif
