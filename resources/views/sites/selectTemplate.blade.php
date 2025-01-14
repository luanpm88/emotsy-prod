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
            <span class="text-semibold"><span class="material-symbols-rounded">library_add</span>
                {{ trans('messages.site.content.setup') }}</span>
        </h1>
    </div>
@endsection

@section('content')
    @include('sites._menu', [
        'menu' => 'content',
    ])

    <div class="d-flex">
        <div class="me-4">
            <div class="card mb-4" style="width:200px">
                <img width="" src="{{ Acelle\Model\Template::shared()->notPreserved()->email()->first()->getThumbUrl() }}" class="card-img-top" alt="...">
            </div>
        </div>
        <div class="">
            <h3>{{ trans('messages.site.template.start_from_scratch') }}</h3>
            <p>{!! trans('messages.site.template.start_from_scratch.intro') !!}</p>
            <div class="mt-4">
                <a link-method="POST" href="{{ action('SiteController@changeTemplate', [
                    'id' => $site->uid,
                    'template_uid' => Acelle\Model\Template::shared()->notPreserved()->site()->first()->uid,
                ]) }}"
                    class="btn btn-primary">
                    {{ trans('messages.site.template.start_from_scratch.start') }}
                </a>
            </div>
        </div>
        
    </div>

    <hr>

    <h3>{{ trans('messages.site.choose_template') }}</h3>
    <p>{!! trans('messages.site.choose_template.intro') !!}</p>

    <div>
        <div class="row">
            @foreach (Acelle\Model\Template::shared()->notPreserved()->site()->get() as $template)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="{{ $template->getThumbUrl() }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <label class="card-title d-block">{{ $template->name }}</label>
                            <p class="card-text d-none">Some quick example text to build on the card title and make up the bulk of the
                                card's content.</p>
                            <a link-method="POST" href="{{ action('SiteController@changeTemplate', [
                                'id' => $site->uid,
                                'template_uid' => $template->uid,
                            ]) }}"
                                class="btn btn-primary">
                                {{ trans('messages.site.template.start_from_scratch.select_template') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


@endsection
