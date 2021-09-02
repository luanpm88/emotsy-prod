@if ($customers->total())
    <a href="{{ action('Admin\CustomerController@index', [
        'keyword' => request()->keyword
    ]) }}" class="search-head border-bottom d-block">
        <div class="d-flex">
            <div class="me-auto">
                <label class="fw-600">
                    <span class="material-icons-outlined me-1">
                        people 
                        </span> {{ trans('messages.customers') }}
                </label>
            </div>
            <div>
                {{ $customers->count() }} / {{ format_number($customers->total()) }} · 
                {{ trans('messages.search.view_all') }}
            </div>
        </div>
    </a>
    @foreach($customers as $customer)
        <a href="{{ action('Admin\CustomerController@edit', $customer->uid) }}" class="search-result border-bottom d-block">
            <div class="d-flex align-items-center">
                <div>
                    <img width="40px" height="40px" class="shadow-sm me-3 rounded-circle"
                        src="{{ $customer->user->getProfileImageUrl() }}" />
                </div>
                <div>
                    <div class="d-flex">
                        <label class="fw-600">
                            {{ $customer->user->displayName() }}
                        </label>
                    </div>
                        
                    <p class="desc text-muted mb-0">
                        {{ $customer->user->email }}
                    </p>
                </div>
            </div>
        </a>
    @endforeach
@endif