@extends('layouts.core.frontend', [
    'menu' => 'site',
])

@section('title', trans('messages.dashboard'))

@section('page_header')
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("SiteController@index") }}">{{ trans('messages.site.landing_pages') }}</a></li>
		</ul>
        <h1>
            <span class="text-semibold"><span class="material-symbols-rounded">tune</span>
                {{ trans('messages.site.page_settings') }}</span>
        </h1>
    </div>
@endsection

@section('content')
    @include('sites._menu', [
        'menu' => 'settings',
    ])

    <h4 class="fw-semibold">{{ trans('messages.site.page_url') }}</h4>
    <p>{{ trans('messages.site.page_url.wording') }}</p>

    <div class="form-group">
        <label class="fw-semibold">{{ trans('messages.site.url') }}</label>
        <div class="input-group mb-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">{{ AppUrl::asset('/') }}</button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">{{ AppUrl::asset('/') }}</a></li>
            </ul>
            <input type="text" class="form-control" value="{{ $site->uid }}" aria-label="Text input with dropdown button">
        </div>
        <span class="d-block small fst-italic text-muted">
            {!! trans('messages.site.url.custom_need_upgrade', [
                'plan' => Auth::user()->customer->getCurrentActiveSubscription()->plan->name,
                'link' => action('SubscriptionController@index'),
            ]) !!}
        </span>
    </div>

    <hr class="mt-5">

    <h4 class="fw-semibold">{{ trans('messages.site.favicon') }}</h4>
    <p>{{ trans('messages.site.favicon.wording') }}</p>

    <div class="d-flex">
        <div class="me-4">
            <div class="bg-light rounded-3 shadow-sm p-3 border text-success" style="
                width: 60px;
                height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
            ">
                <span class="text-muted2">
                    <svg style="fill: currentColor;" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-480ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h320v80H200v560h560v-320h80v320q0 33-23.5 56.5T760-120H200Zm40-160h480L570-480 450-320l-90-120-120 160Zm440-320v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80Z"/></svg>
                </span>
            </div>
        </div>
        <div>
            <div class="mb-2">
                <button type="button" class="btn btn-light">{{ trans('messages.site.favicon.choose_image') }}</button>
            </div>
            <p class="mb-0 small fst-italic text-muted">{{ trans('messages.site.favicon.image_guide') }}</p>
        </div>
    </div>

    <hr class="mt-5">

    <h4 class="fw-semibold">{{ trans('messages.site.settings') }}</h4>
    <p class="mb-3">{{ trans('messages.site.settings.wording') }}</p>

    
    <ul class="nav nav-tabs nav-tabs-top nav-underline mb-4 fst-italic">
        <li class="nav-item">
            <a href="javascript:;" class="nav-link active" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo-tab-pane" role="tab">
                <span class="d-flex align-items-center">
                    <span>{{ trans('messages.site.settings.seo_settings') }}</span>
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:;" class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social-tab-pane" role="tab">
                <span class="d-flex align-items-center">
                    <span>{{ trans('messages.site.settings.social_share') }}</span>
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a  href="javascript:;" class="nav-link" id="custom-tab" data-bs-toggle="tab" data-bs-target="#custom-tab-pane" role="tab">
                <span class="d-flex align-items-center">
                    <span>{{ trans('messages.site.settings.analytics_custom') }}</span>
                </span>
            </a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="seo-tab-pane" role="tabpanel" aria-labelledby="seo-tab" tabindex="0">
            <div class="form-group">
                <label class="fw-semibold">{{ trans('messages.site.page_title') }}</label>
                @include('helpers.form_control.text', [
                    'name' => 'page[page_title]',
                    'value' => '',
                    'attributes' => [
                        'required' => 'required',
                    ],
                ])
                <p class="mb-0 small fst-italic text-muted mt-2">
                    {{ trans('messages.site.page_title.wording') }}
                </p>
            </div>

            <div class="form-group">
                <label class="fw-semibold">{{ trans('messages.site.page_keywords') }}</label>
                @include('helpers.form_control.textarea', [
                    'name' => 'page[page_keywords]',
                    'value' => '',
                    'attributes' => [
                        'required' => 'required',
                    ],
                ])
                <p class="mb-0 small fst-italic text-muted mt-2">
                    {{ trans('messages.site.page_keywords.wording') }}
                </p>
            </div>

            <div class="form-group">
                <label class="fw-semibold">{{ trans('messages.site.page_description') }}</label>
                @include('helpers.form_control.textarea', [
                    'name' => 'page[page_description]',
                    'value' => '',
                    'attributes' => [
                        'required' => 'required',
                    ],
                ])
                <p class="mb-0 small fst-italic text-muted mt-2">
                    {{ trans('messages.site.page_description.wording') }}
                </p>
            </div>

            <div class="form-group">
                <label class="fw-semibold">{{ trans('messages.site.site_indexing') }}</label>
                <p class="mb-2 small fst-italic text-muted mt-2">
                    {{ trans('messages.site.site_indexing.wording') }}
                </p>
                @include('helpers.form_control.switcher', [
                    'name' => 'page[site_indexing]',
                    'value' => '',
                    'on_value' => 'yes',
                    'off_value' => 'no',
                ])
            </div>
        </div>
        <div class="tab-pane fade" id="social-tab-pane" role="tabpanel" aria-labelledby="social-tab" tabindex="0">
            <div class="form-group">
                <label class="fw-semibold">{{ trans('messages.site.social_share_title') }}</label>
                @include('helpers.form_control.text', [
                    'name' => 'page[social_share_title]',
                    'value' => '',
                    'attributes' => [
                        'required' => 'required',
                    ],
                ])
                <p class="mb-0 small fst-italic text-muted mt-2">
                    {{ trans('messages.site.social_share_title.wording') }}
                </p>
            </div>
            <div class="form-group">
                <label class="fw-semibold">{{ trans('messages.site.social_share_description') }}</label>
                @include('helpers.form_control.textarea', [
                    'name' => 'page[social_share_description]',
                    'value' => '',
                    'attributes' => [
                        'required' => 'required',
                    ],
                ])
                <p class="mb-0 small fst-italic text-muted mt-2">
                    {{ trans('messages.site.social_share_description.wording') }}
                </p>
            </div>
            
            <div class="form-group">
                <label class="fw-semibold">{{ trans('messages.site.social_share_image') }}</label>
                <div class="d-flex">
                    <div class="me-4">
                        <div class="bg-light rounded-3 shadow-sm p-3 border text-success" style="
                            width: 60px;
                            height: 60px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        ">
                            <span class="text-muted2">
                                <svg style="fill: currentColor;" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-480ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h320v80H200v560h560v-320h80v320q0 33-23.5 56.5T760-120H200Zm40-160h480L570-480 450-320l-90-120-120 160Zm440-320v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80Z"/></svg>
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="mb-2">
                            <button type="button" class="btn btn-light">{{ trans('messages.site.favicon.choose_image') }}</button>
                        </div>
                        <p class="mb-0 small fst-italic text-muted mt-2">
                            {!! trans('messages.site.social_share_image.wording')  !!}
                        </p>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="tab-pane fade" id="custom-tab-pane" role="tabpanel" aria-labelledby="custom-tab" tabindex="0">
            <div class="form-group">
                <label class="fw-semibold">{{ trans('messages.site.google_analystics') }}</label>
                @include('helpers.form_control.text', [
                    'name' => 'page[google_analystics]',
                    'value' => '',
                    'attributes' => [
                        'required' => 'required',
                        'placeholder' => 'UA-XXXXX-X or G-XXXXXXX',
                    ],
                ])
                <p class="mb-0 small fst-italic text-muted mt-2">
                    {{ trans('messages.site.google_analystics.wording') }}
                </p>
            </div>
            <div class="form-group">
                <label class="fw-semibold">{{ trans('messages.site.meta_pixel') }}</label>
                @include('helpers.form_control.text', [
                    'name' => 'page[meta_pixel]',
                    'value' => '',
                    'attributes' => [
                        'required' => 'required',
                        'placeholder' => 'e.g 123456789012345',
                    ],
                ])
                <p class="mb-0 small fst-italic text-muted mt-2">
                    {{ trans('messages.site.meta_pixel.wording') }}
                </p>
            </div>
            <div class="form-group">
                <label class="fw-semibold">{{ trans('messages.site.header_code_injection') }}</label>
                @include('helpers.form_control.textarea', [
                    'name' => 'page[header_code_injection]',
                    'value' => '',
                    'attributes' => [
                        'required' => 'required',
                    ],
                ])
                <p class="mb-0 small fst-italic text-muted mt-2">
                    {{ trans('messages.site.header_code_injection.wording') }}
                </p>
            </div>
            <div class="form-group">
                <label class="fw-semibold">{{ trans('messages.site.page_code_injection') }}</label>
                @include('helpers.form_control.textarea', [
                    'name' => 'page[page_code_injection]',
                    'value' => '',
                    'attributes' => [
                        'required' => 'required',
                    ],
                ])
                <p class="mb-0 small fst-italic text-muted mt-2">
                    {{ trans('messages.site.page_code_injection.wording') }}
                </p>
            </div>
        </div>
    </div>

    <hr >
    <div class="text-end">
        <a href="{{ action('SiteController@index') }}" class="btn btn-link me-1">
            <span class="material-symbols-rounded">
                keyboard_arrow_left
            </span>
            {{ trans('messages.go_back') }}
        </a>
        <button class="btn btn-primary me-1"><i class="icon-check"></i> {{ trans('messages.site.save_and_continue') }}</button>
    </div>

@endsection
