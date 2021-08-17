@if (Auth::user()->customer->logs()->count() == 0)
    <div class="empty-list">
        <span class="material-icons-outlined">
            auto_awesome
        </span>
        <span class="line-1">
            {{ trans('messages.no_activity_logs') }}
        </span>
    </div>
@else
    <div class="action-log-box">
        <!-- Timeline -->
        <div class="">
            <div class="">
                @foreach (Auth::user()->customer->logs()->take(20)->get() as $log)
                    <!-- Sales stats -->
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <a href="#"><img width="50px" class="rounded-circle shadow-sm" src="{{ $log->customer->user->getProfileImageUrl() }}" alt=""></a>
                        </div>

                        <div class="card px-0 shadow-sm container-fluid">
                            <div class="card-body pt-2">
                                <div class="d-flex align-items-center pt-1">
                                    <label class="panel-title text-semibold my-0 fw-600">{{ $log->customer->user->displayName() }}</label>
                                    <div class="d-flex align-items-center ms-auto text-muted">
                                        <span style="font-size: 18px" class="material-icons-outlined ms-auto me-2">
                                            history
                                        </span>
                                        <div class="">
                                            <span class="heading-text"><i class="icon-history position-left text-success"></i> {{ Tool::dateTime($log->created_at)->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0">{!! $log->message() !!}</p>
                            </div>
                        </div>
                    </div>
                    <!-- /sales stats -->
                @endforeach
            </div>
        </div>
    </div>
@endif


{{-- @extends('layouts.popup.large')

@section('title')
    {{ trans('messages.activities') }}
@endsection

@section('content')
    <div id="ActivityPopupContainer"
    data-url="{{ action('AccountController@logsListing') }}"
    per-page="{{ Acelle\Model\Log::$itemsPerPage }}"                
    >                
    <div class="row top-list-controls py-2">
        <div class="col-md-10">               
            <div class="filter-box">
                <span class="filter-group">
                    <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                    <select class="select" name="sort_order">
                        <option value="created_at">{{ trans('messages.created_at') }}</option>
                    </select>                                        
                    <input type="hidden" name="sort_direction" value="desc" />
<button type="button" class="btn btn-xs sort-direction" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" role="button" class="btn btn-xs">
                        <span class="material-icons-outlined desc">
sort
</span>
                    </button>
                </span>
                <span class="filter-group ml-10">
                    <span class="title text-semibold text-muted">{{ trans('messages.type') }}</span>
                    <select class="select" name="type">
                        <option value="">{{ trans('messages.all') }}</option>
                        <option value="list">{{ trans('messages.list') }}</option>
                        <option value="segment">{{ trans('messages.segment') }}</option>
                        <option value="page">{{ trans('messages.page') }}</option>
                        <option value="subscriber">{{ trans('messages.subscriber') }}</option>
                        <option value="campaign">{{ trans('messages.campaign') }}</option>
                    </select>                                        
                </span>
            </div>
        </div>
    </div>

    <div id="ActivityPopupContent" class="pml-table-container">
        
        
        
    </div>
    </div>

    <script>
        var ActivityPopup = {
            getList: function() {
                return makeList({
                    url: '{{ action('AccountController@logsListing') }}',
                    container: $('#ActivityPopupContainer'),
                    content: $('#ActivityPopupContent')
                });
            }
        };

        $(document).ready(function() {
            ActivityPopup.getList().load();
        });
    </script>
@endsection --}}