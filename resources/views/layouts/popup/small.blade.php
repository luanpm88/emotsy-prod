<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                <i class="material-icons-outlined alert-icon mr-2">contact_support</i>
                @yield('title')
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body @yield('class')">
            <!-- display flash message -->
            @include('common.errors')

            <!-- main inner content -->
            @yield('content')
        </div>
    </div>
</div>