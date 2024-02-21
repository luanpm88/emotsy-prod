@extends('layouts.core.backend', [
	'menu' => 'plan_group',
])

@section('title', $planGroup->name)

@section('head')
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Admin\PlanGroupController@index") }}">{{ trans('messages.plan_groups') }}</a></li>
        </ul>
        <h1 class="mc-h1">
            <span class="text-semibold">{{ $planGroup->name }}</span>
        </h1>
    </div>

@endsection

@section('content')
    
    @include('admin.plan_groups._menu', [
        'menu' => 'features',
    ])
    
    <form enctype="multipart/form-data" action="{{ action('Admin\PlanGroupController@features', $planGroup->uid) }}" method="POST" class="form-validate-jqueryx">
        <div class="row">
            <div class="col-md-8">
                {{ csrf_field() }}
                <div class="mc_section">
                    <h2>{{ trans('messages.plan_group.features') }}</h2>

                    <table class="table" style="width:auto">
                        {{-- @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'seats',
                        ]) --}}

                        {{-- @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'custom_user_permissions',
                        ]) --}}
                        
                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'content_approval',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'embedded_form',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'number',
                            'name' => 'popup',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'number',
                            'name' => 'segmentation',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'subscriber_management',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'drag_drop_builder',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'rich_text_editor',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'image_library',
                            'beta' => true,
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'mobile_friendly_newsletter',
                            'beta' => true,
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'newsletter_templates',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'create_own_template',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'remove_logo',
                        ])

                        {{-- @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'custom_html_builder',
                        ]) --}}


                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'ai_writing',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'automation_builder',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'multiple_automation_trigger',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'number',
                            'name' => 'automation_step',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'rss_campaigns',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'api_documentation',
                        ])


                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'template_integration',
                            'beta' => true,
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'number',
                            'name' => 'custom_variables',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'ab_testing',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'preview_test_email',
                        ])


                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'dynamic_email_content',
                            'beta' => true,
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'number',
                            'name' => 'unsubscribe_page_builder',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'list_growth_reporting',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'individual_subscriber_profile',
                        ])



                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'automation_real_time_report',
                            'beta' => true,
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'automation_real_time_report',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'knowledge_base',
                            'beta' => true,
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => 'email_support',
                        ])

                        @include('admin.plan_groups.featuresControl', [
                            'type' => 'boolean',
                            'name' => '24_7_chat',
                        ])
                        {{-- <tr>
                            <th class="fw-normal" valign="middle">
                                {{ trans('messages.plan.feature.embedded_form') }}
                            </th>
                            <td>
                                <label class="checker ms-2">
                                    <input type="hidden" name="features[embedded_form]" value="0">
                                    <input {{ $planGroup->getFeature('embedded_form') ? " checked" : "" }} type="checkbox"
                                        name="features[embedded_form]" value="1" class="styled4">
                                    <span class="checker-symbol"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th class="fw-normal" valign="middle">
                                {{ trans('messages.plan.feature.popup') }}
                            </th>
                            <td>
                                <input type="number" name="features[popup]" value="{{ $planGroup->getFeature('popup') }}"
                                    class="form-control form-control-sm rd-10 {{ $errors->has('popup') ? 'is-invalid' : '' }}"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th class="fw-normal" valign="middle">
                                {{ trans('messages.plan.feature.segmentation') }}
                            </th>
                            <td>
                                <input type="number" name="features[segmentation]" value="{{ $planGroup->getFeature('segmentation') }}"
                                    class="form-control form-control-sm rd-10 {{ $errors->has('segmentation') ? 'is-invalid' : '' }}"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th class="fw-normal" valign="middle">
                                {{ trans('messages.plan.feature.subscriber_management') }}
                            </th>
                            <td>
                                <label class="checker ms-2">
                                    <input type="hidden" name="features[subscriber_management]" value="0">
                                    <input {{ $planGroup->getFeature('subscriber_management') ? " checked" : "" }} type="checkbox"
                                        name="features[subscriber_management]" value="1" class="styled4">
                                    <span class="checker-symbol"></span>
                                </label>
                            </td>
                        </tr> --}}
                    </table>
                    
                </div>
                <button type="submit" class="btn btn-secondary me-2">{{ trans('messages.save') }}</button>
                <a href="{{ action('Admin\PlanGroupController@index') }}" role="button" class="btn btn-link">
                    {{ trans('messages.cancel') }}
                </a>
            </div>
        </div>
    </form>
@endsection
