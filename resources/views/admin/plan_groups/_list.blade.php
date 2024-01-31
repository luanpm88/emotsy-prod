@if ($planGroups->count() > 0)
    <table class="table table-box pml-table mt-2"
        current-page="{{ empty(request()->page) ? 1 : empty(request()->page) }}"
    >
        @foreach ($planGroups as $key => $planGroup)
            <tr class="position-relative">
                <td width="1%" class="list-check-col">
                    <div class="text-nowrap d-flex align-items-canter">
                        @if (!$planGroup->visible)
                            <a
                                title="{{ trans('messages.plan_group.show') }}"
                                link-method="POST"
                                href="{{ action('Admin\PlanGroupController@visibleOn', $planGroup->uid) }}"
                                class="list-action-single plan-off {{ (\Auth::user()->can('visibleOn', $planGroup) ? 'xtooltip' : 'cant_show') }}"
                            >
                                <i class="material-symbols-rounded plan-off-icon fs-3 me-3">toggle_off</i>
                            </a>
                        @else
                            <a
                                title="{{ trans('messages.plan_group.hide') }}"
                                link-confirm="{{ trans('messages.plan_group.hide.confirm') }}"
                                link-method="POST"
                                href="{{ action('Admin\PlanGroupController@visibleOff', $planGroup->uid) }}"
                                class="list-action-single plan-on {{ (\Auth::user()->can('visibleOff', $planGroup) ? 'xtooltip' : 'disabled') }}"
                            >
                                <i class="material-symbols-rounded plan-on-icon fs-3 me-3">toggle_on</i>
                            </a>
                        @endif                        
                    </div>
                </td>
                <td width="60%">
                    <h5 class="m-0 text-bold">
                        <span class="kq_search d-block" href="{{ action('Admin\PlanGroupController@general', $planGroup->uid) }}">
                            {{ $planGroup->name }}
                        </span>
                    </h5>
                    <p class="mb-0">{{ $planGroup->description }}</p>
                </td>
                <td>
                    <h5 class="no-margin text-bold kq_search">
                        {{ $planGroup->displayFrequencyTime() }}
                    </h5>
                    <span class="text-muted">{{ trans('messages.plan.billing_cycle') }}</span>
                </td>
                <td>
                    <h5 class="no-margin text-bold kq_search">
                        <a href="{{ action('Admin\PlanController@index', [
                            'plan_group_uid' => $planGroup->uid,
                        ]) }}" class="">
                            {{ $planGroup->plans()->count() }}
                        </a>
                    </h5>
                    <span class="text-muted">{{ trans('messages.plan_group.plans') }}</span>
                </td>
                <td class="text-end text-nowrap pe-0" width="5%">
                    @can('update', $planGroup)
                        <a href="{{ action('Admin\PlanGroupController@general', $planGroup->uid) }}" role="button" class="btn btn-secondary btn-icon"> <span class="material-symbols-rounded">edit</span> {{ trans('messages.edit') }}</a>
                    @endcan
                    @if (\Auth::user()->can('delete', $planGroup) ||
                         \Auth::user()->can('copy', $planGroup)
                    )
                        <div class="btn-group">
                            <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @can('copy', $planGroup)
                                    <li>
                                        <a
                                            href="{{ action('Admin\PlanGroupController@copy', ['copy_plan_uid' => $planGroup->uid]) }}"
                                            class="dropdown-item copy-plan-link" title="{{ trans('messages.copy') }}"
                                        >
                                            <span class="material-symbols-rounded">content_copy</span> {{ trans('messages.copy') }}
                                        </a>
                                    </li>
                                  @endcan
                                @can('delete', $planGroup)
                                    <li>
                                        <a class="dropdown-item list-action-single"
                                            link-confirm-url="{{ action('Admin\PlanGroupController@deleteConfirm', ['uids' => $planGroup->uid]) }}" href="{{ action('Admin\PlanGroupController@delete', ['uids' => $planGroup->uid]) }}" title="{{ trans('messages.delete') }}" class="">
                                            <span class="material-symbols-rounded">delete</span> {{ trans('messages.delete') }}
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
    @include('elements/_per_page_select', ["items" => $planGroups])
    

    <script>
        var PlanList = {
			copyPopup: null,

			getCopyPopup: function() {
				if (this.copyPopup === null) {
					this.copyPopup = new Popup();
				}

				return this.copyPopup;
			}
		}

        $(function() {
            $('.copy-plan-link').on('click', function(e) {
                e.preventDefault();			
                var url = $(this).attr('href');

                PlanList.getCopyPopup().load({
                    url: url
                });
            });

            $('.cant_show').click(function(e) {
                e.preventDefault();

                var confirm = `{{ trans('messages.plan_group.cant_show') }}`;
                var dialog = new Dialog('alert', {
                    message: confirm
                })
            });

            $('.enable-plan').click(function(e) {
                e.preventDefault();

                var confirm = `{{ trans('messages.plan_group.enable_and_visible.confirm') }}`;
                var href_yes = $(this).attr('href_yes');
                var href_no = $(this).attr('href_no');

                var dialog = new Dialog('yesno', {
                    message: confirm,
                    no: function(dialog) {
                        $.ajax({
                            url: href_no,
                            method: 'POST',
                            data: {
                                _token: CSRF_TOKEN,
                            },
                            statusCode: {
                                // validate error
                                400: function (res) {
                                    alert('Something went wrong!');
                                }
                            },
                            success: function (response) {
                                // notify
                                notify({
        type: 'success',
        title: '{!! trans('messages.notify.success') !!}',
        message: response.message
    });
                            }
                        });
                    },
                    yes: function(dialog) {                    
                        $.ajax({
                            url: href_yes,
                            method: 'POST',
                            data: {
                                _token: CSRF_TOKEN,
                            },
                            statusCode: {
                                // validate error
                                400: function (res) {
                                    alert('Something went wrong!');
                                }
                            },
                            success: function (response) {
                                // notify
                                notify({
        type: 'success',
        title: '{!! trans('messages.notify.success') !!}',
        message: response.message
    });
                            }
                        });
                    },
                });
            });
        });
    </script>
@elseif (!empty(request()->keyword))
    <div class="empty-list">
        <i class="material-symbols-rounded">assignment_turned_in</i>
        <span class="line-1">
            {{ trans('messages.no_search_result') }}
        </span>
    </div>
@else
    <div class="empty-list">
        <i class="material-symbols-rounded">assignment_turned_in</i>
        <span class="line-1">
            {{ trans('messages.plan_group.empty_line_1') }}
        </span>
    </div>
@endif
