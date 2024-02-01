<h4>{{ trans('messages.delete_plan_confirm_warning') }}</h4>
<ul class="modern-listing">
    @foreach ($planGroups->get() as $planGroup)
        <li class="d-flex align-items-center">
            <i class="material-symbols-rounded fs-4 me-3 text-danger">error_outline</i>
            <div>
                <h5 class="text-danger mb-1">{{ $planGroup->name }}</h5>
                <p class="text-muted">
                    @if ($planGroup->plans()->count())
                        <span class="text-bold text-danger">{{ $planGroup->plans()->count() }}</span> {{ trans('messages.plan_group.plans') }}<pp>,</pp>
                    @else
                    <span class="text-bold text-muted">{{ $planGroup->plans()->count() }}</span> {{ trans('messages.plan_group.plans') }}<pp>,</pp>
                    @endif
                </p>  
            </div>                      
        </li>
    @endforeach
</ul>