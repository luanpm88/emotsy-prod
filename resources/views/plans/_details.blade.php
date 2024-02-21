<style>
    .lastUnit.size1of2 {
        text-align: right;
    }
</style>

{{-- Monthly email sends
Seats
Custom user permissions
Content approval
Grow your audience
Embedded forms
Pop-ups
Segmentation
Subscriber management
Send amazing email campaigns
Drag & Drop Newsletter editor
Rich-text newsletter editor
Image library
Mobile-friendly newsletters
Newsletter templates
Create your own newsletter template
Remove Emotsy Logo

Custom HTML newsletter editor
AI writing assistant

Automate your work
Email automation builder
Multiple automation triggers
Automation steps
RSS campaigns
API documentation
Template integrations
Optimize for audiences
Custom variables
Email A/B split testing
Preview and send test emails
Dynamic email content
Unsubscribe page builder
Analyze reports
List growth reporting
Individual subscriber profiles
Automation real-time reports
Get support
Knowledge base
Email support
24/7 Live chat support --}}

<h5 class="mt-4 mb-2">{{ trans('messages.plan.features') }}</h5>
<ul class="dotted-list topborder section">
    <li class="py-1 fw-semibold bg-light ps-3">
        {{ trans('messages.plan.feature.general') }}
    </li>
    <li class="selfclear ps-3 d-none">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan_name') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag><span>{{ $plan->name }}</span></mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.sending_quota_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag class='fw-bold'>{{ $plan->displayTotalQuota() }}</mc:flag>
        </div>
    </li>
    {{-- <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.max_lists_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{{ $plan->displayMaxList() }}</mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.max_subscribers_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{{ $plan->displayMaxSubscriber() }}</mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.max_campaigns_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{{ $plan->displayMaxCampaign() }}</mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.max_size_upload_total_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{{ $plan->displayMaxSizeUploadTotal() }} MB</mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.max_file_size_upload_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{{ $plan->displayFileSizeUpload() }} MB</mc:flag>
        </div>
    </li> --}}
    <li class="py-1 fw-semibold bg-light ps-3">
        {{ trans('messages.plan.feature.grow_audience') }}
    </li>
    {{-- <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.seats') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('seats'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li> --}}
    {{-- <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.custom_user_permissions') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('custom_user_permissions'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li> --}}
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.content_approval') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('content_approval'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.embedded_form') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('embedded_form'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.popup') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{{ $plan->getFeature('popup') }}</mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.segmentation') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{{ $plan->getFeature('segmentation') }}</mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.subscriber_management') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('subscriber_management'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>





    <li class="py-1 fw-semibold bg-light ps-3">
        {{ trans('messages.plan.feature.amazing_email_campaign') }}
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.drag_drop_builder') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('drag_drop_builder'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.rich_text_editor') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('rich_text_editor'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.image_library') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('image_library'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.mobile_friendly_newsletter') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('mobile_friendly_newsletter'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.newsletter_templates') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('newsletter_templates'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.create_own_template') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('create_own_template'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.remove_logo') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('remove_logo'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    {{-- <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.custom_html_builder') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('custom_html_builder'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li> --}}
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.ai_writing') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('ai_writing'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>



    <li class="py-1 fw-semibold bg-light ps-3">
        {{ trans('messages.plan.feature.automate_your_work') }}
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.automation_builder') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('automation_builder'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.multiple_automation_trigger') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('multiple_automation_trigger'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.automation_step') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{{ $plan->getFeature('automation_step') }}</mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.rss_campaigns') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('rss_campaigns'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.api_documentation') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('api_documentation'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.template_integration') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('template_integration'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>





    <li class="py-1 fw-semibold bg-light ps-3">
        {{ trans('messages.plan.feature.optimize_for_audiences') }}
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.custom_variables') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('custom_variables'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.ab_testing') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('ab_testing'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.preview_test_email') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('preview_test_email'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.dynamic_email_content') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('dynamic_email_content'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>

    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.unsubscribe_page_builder') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('unsubscribe_page_builder'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>





    <li class="py-1 fw-semibold bg-light ps-3">
        {{ trans('messages.plan.feature.analyze_report') }}
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.list_growth_reporting') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('list_growth_reporting'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.individual_subscriber_profile') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('individual_subscriber_profile'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.automation_real_time_report') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('automation_real_time_report'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>





    <li class="py-1 fw-semibold bg-light ps-3">
        {{ trans('messages.plan.feature.get_support') }}
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.knowledge_base') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('knowledge_base'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.automation_real_time_report') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('automation_real_time_report'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.knowledge_base') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('knowledge_base'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>

    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.email_support') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('email_support'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>

    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.plan.feature.24_7_chat') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>
                @if ($plan->getFeature('24_7_chat'))
                    <span class="material-icons-outlined text-success" style="transform:scale(1.4)">
                        task_alt
                    </span>
                @else
                    <span class="material-icons-outlined text-danger" style="transform:scale(1.4)">
                        do_not_disturb_on
                    </span>
                @endif
            </mc:flag>
        </div>
    </li>
</ul>





<ul class="dotted-list topborder section d-none">
    <li>
        <div class="unit size1of2 d-none">
            <span>{{ trans('messages.plan_name') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag><span>{{ $plan->name }}</span></mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 d-none">
            <span>{{ trans('messages.price') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag><span>{{ Acelle\Library\Tool::format_price($plan->price, $plan->currency->format) }}</span></mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.sending_quota_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{{ $plan->displayTotalQuota() }}</mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.max_lists_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{{ $plan->displayMaxList() }}</mc:flag>
        </div>
    </li>
        
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.max_subscribers_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{{ $plan->displayMaxSubscriber() }}</mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.max_campaigns_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{{ $plan->displayMaxCampaign() }}</mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.max_size_upload_total_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{{ $plan->displayMaxSizeUploadTotal() }}</mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.max_file_size_upload_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{{ $plan->displayFileSizeUpload() }}</mc:flag>
        </div>
    </li>
        
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.allow_create_sending_servers_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{!! $plan->displayAllowCreateSendingServer() !!}</mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.allow_create_sending_domains_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{!! $plan->displayAllowCreateSendingDomain() !!}</mc:flag>
        </div>
    </li>
    <li class="selfclear ps-3">
        <div class="unit size1of2 text-nowrap">
            <span>{{ trans('messages.allow_create_email_verification_servers_label') }}</span>
        </div>
        <div class="lastUnit size1of2 ms-auto">
            <mc:flag>{!! $plan->displayAllowCreateEmailVerificationServer() !!}</mc:flag>
        </div>
    </li>
</ul>
