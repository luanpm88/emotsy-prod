@extends('layouts.core.dark')

@section('title', trans('messages.edit_template'))

@section('head')
	<script type="text/javascript" src="{{ URL::asset('core/tinymce/tinymce.min.js') }}"></script>        
    <script type="text/javascript" src="{{ URL::asset('core/js/editor.js') }}"></script>

    <script src="{{ URL::asset('assets2/js/UrlAutoFill.js') }}"></script>
@endsection

@section('menu_title')
    <li class="d-flex align-items-center">
        <div class="d-inline-block d-flex mr-auto align-items-center ml-3 lvl-1">
            <h4 class="my-0 me-2 menu-title">{{ $campaign->name }}</h4>
            <i class="material-icons-outlined">alarm</i>
        </div>
    </li>
@endsection

@section('menu_right')
    <li class="nav-item">
        <a  href="javascript:;"
            onclick="parent.$('body').removeClass('overflow-hidden');parent.$('.full-iframe-popup').fadeOut()"
            class="nav-link d-flex align-items-center py-3 lvl-1">
            <i class="material-icons-outlined me-2">arrow_back</i>
            <span>{{ trans('messages.go_back') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ action('CampaignController@builderClassic', [
            'uid' => $campaign->uid
        ]) }}"
            class="nav-link d-flex align-items-center py-3 lvl-1">
            <span>{{ trans('messages.campaign.html_editor') }}</span>
        </a>
    </li>
    <li class="d-flex align-items-center px-3">
        <button class="btn btn-primary" onclick="$('#classic-builder-form').submit()">{{ trans('messages.save') }}</button>
    </li>
    <li>
        <a href="javascript:;"
            onclick="parent.$('body').removeClass('overflow-hidden');parent.$('.full-iframe-popup').fadeOut()"
            class="nav-link close-button action black-close-button">
            <i class="material-icons-outlined">close</i>
        </a>
    </li>
@endsection

@section('content')
    <form style="" id="classic-builder-form" action="{{ action('CampaignController@builderPlainEdit', $campaign->uid) }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}

        <div class="row mr-0 ml-0 form-groups-bottom-0">
            <div class="col-md-9 pl-0 pb-0 pr-0 form-group-mb-0">
                @include('helpers.form_control', ['type' => 'textarea',
                    'class' => 'campaign-plain-text',
                    'name' => 'plain',
                    'label' => '',
                    'value' => $campaign->plain,
                    'rules' => [],
                    'help_class' => 'campaign'
                ])          
            </div>
            <div class="col-md-3 pr-0 pb-0 sidebar pr-4 pt-4 pl-4" style="overflow:auto;background:#f5f5f5">
                @include('elements._tags', ['tags' => Acelle\Model\Template::tags($campaign->defaultMailList)])
            </div>            
        </div>   
    <form>

    <script>
    $('#classic-builder-form').submit(function(e) {
            e.preventDefault();

            tinymce.triggerSave();

            var url = $(this).attr('action');
            var data = $(this).serialize();

            if ($(this).valid()) {
                // open builder effects
                addMaskLoading("{{ trans('messages.automation.template.saving') }}", function() {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                        statusCode: {
                            // validate error
                            400: function (res) {
                                removeMaskLoading();
                                
                                // notify
                                parent.notify('error', '{{ trans('messages.notify.error') }}', res.responseText);
                            }
                        },
                        success: function (response) {
                            removeMaskLoading();

                            if (typeof(parent.builderSelectPopup) != 'undefined') {
                                parent.builderSelectPopup.hide();
                            }

                            // notify
                            parent.notify({
    type: 'success',
    title: '{!! trans('messages.notify.success') !!}',
    message: response.message
});
                        }
                    });
                });         
            }     
        });

        $('.sidebar').css('height', parent.$('.full-iframe-popup').height()-53);
        $('[name=plain]').css('height', parent.$('.full-iframe-popup').height()-53);
    </script>
@endsection
