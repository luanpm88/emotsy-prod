<div class="card subscription-step">
    <a 
        link-method="POST" link-confirm="{{ trans('messages.subscription.change_init_plan.confirm') }}"
        href="{{ action('SubscriptionController@cancelInvoice', [
            'invoice_uid' => $invoice->uid,
        ]) }}"
        class="card-header py-3 d-block select-plan-tab">
        <div class="d-flex align-items-center">
            <div class="me-3"><label class="subscription-step-number bg-secondary">1</label></div>
            <div>
                <h5 class="fw-600 mb-0 fs-6 text-start">
                    {{ trans('messages.subscription.plan_chosen', [
                        'plan' => $invoice->getBillingInfo()['plan']->name
                    ]) }}
                </h5>
                <p class="m-0 text-muted">{!! $invoice->getBillingInfo()['description'] !!}</p>
            </div>
            <div class="ms-auto">
                <span class="material-icons-outlined fs-4 text-success">
                    task_alt
                    </span>
            </div>
        </div>
    </a>
</div>