@extends('layouts.popup.medium')

@section('content')
	<p class="mb-4">
        <strong>Send-an-email action</strong> Plase folow the wizard below to create email information/content for sending to customers.
        Make sure you fill-up all neccessary information for an email.
    </p>
	
	<ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link" href="javascript:;" onclick="popup.load('{{ action('Automation2Controller@emailSetup', [
                'uid' => $automation->uid,
                'email_uid' => $email->uid,
            ]) }}')">
                <i class="lnr lnr-cog mr-2"></i>
                {{ trans('messages.automation.email.setup') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active {{ (!isset($email->id)) ? 'disabled' : '' }}" href="javascript:;">
                <i class="lnr lnr-layers mr-2"></i>
                {{ trans('messages.automation.email.template') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (!isset($email->id)) ? 'disabled' : '' }}" href="javascript:;"
                @if (isset($email->id))                    
                    onclick="popup.load('{{ action('Automation2Controller@emailConfirm', [
                        'uid' => $automation->uid,
                        'email_uid' => $email->uid,
                    ]) }}')"
                @endif
            >
                <i class="lnr lnr-cog mr-2"></i>
                {{ trans('messages.automation.email.confirm') }}
            </a>
        </li>
    </ul>
	
	<div class="row">
        <div class="col-md-12">
            <h4 class="mb-3 mt-2">{{ trans('messages.automation.choose_your_template_layout') }}</h4>
			<p class="mb-4">{{ trans('messages.automation.choose_your_template_layout.intro') }}</p>
                
            <ul class="nav nav-tabs campaign-template-tabs">
                <li class="nav-item">
					<a class="nav-link" onclick="popup.load('{{ action('Automation2Controller@templateLayout', [
							'uid' => $automation->uid,
							'email_uid' => $email->uid,
						]) }}')"
					>
						Layouts
					</a>
				</li>
                <li class="nav-item">
					<a class="nav-link active" onclick="popup.load('{{ action('Automation2Controller@templateTheme', [
							'uid' => $automation->uid,
							'email_uid' => $email->uid,
						]) }}')"
					>
						Themes
					</a>
				</li>
                <li class="nav-item">
					<a class="nav-link" onclick="popup.load('{{ action('Automation2Controller@templateUpload', [
							'uid' => $automation->uid,
							'email_uid' => $email->uid,
						]) }}')"
					>
						Upload
					</a>
				</li>
            </ul>
                
            <div id="gallery">
                <form class="listing-form mt-5"
                    data-url="{{ action('CampaignController@templateThemeList', $automation->uid) }}"
                    per-page="{{ Acelle\Model\Template::$itemsPerPage }}"					
                >				
                    <div class="row top-list-controls">
                        <div class="col-md-9">		
                                <div class="filter-box">										
                                    <span class="filter-group">
                                        <span class="title text-semibold text-muted">{{ trans('messages.from') }}</span>
                                        <select class="select" name="from">
                                            <option value="all">{{ trans('messages.all') }}</option>
                                            <option value="mine">{{ trans('messages.my_templates') }}</option>
                                            <option value="gallery" selected='selected'>{{ trans('messages.gallery') }}</option>
                                        </select>										
                                    </span>
                                    <span class="filter-group">
                                        <span class="title text-semibold text-muted">{{ trans('messages.sort_by') }}</span>
                                        <select class="select" name="sort-order">
                                            <option value="custom_order" class="active">{{ trans('messages.custom_order') }}</option>
                                            <option value="name">{{ trans('messages.name') }}</option>
                                            <option value="created_at">{{ trans('messages.created_at') }}</option>
                                        </select>										
                                        <button class="btn btn-xs sort-direction" rel="asc" data-popup="tooltip" title="{{ trans('messages.change_sort_direction') }}" type="button" class="btn btn-xs">
                                            <i class="icon-sort-amount-asc"></i>
                                        </button>
                                    </span>
                                    <span class="text-nowrap">
                                        <input name="search_keyword" class="form-control search" placeholder="{{ trans('messages.type_to_search') }}" />
                                        <i class="icon-search4 keyword_search_button"></i>
                                    </span>
                                </div>
                        </div>
                    </div>
                    
                    <div class="pml-table-container">
                        
                        
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
        
    <script>
        $('.campaign-template-tabs a').click(function(e) {
            e.preventDefault();
        
            var url = $(this).attr('href');
        
            templatePopup.load(url);
        });
    </script>
@endsection