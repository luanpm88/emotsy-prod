@extends('layouts.backend')

@section('title', trans('messages.geoip.title'))

@section('page_header')

	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
		</ul>
		<h1>
			<span class="text-semibold"><i class="icon-list2"></i> {{ trans('messages.geoip.title') }}</span>
		</h1>
	</div>

@endsection

@section('content')

    <p>{{ trans('messages.geoip.description') }}</p>

    <div class="pml-table-container">
        <table class="table table-box pml-table">
            <tbody>
                <tr>
                    <td class="plugin-title-column plugin-title-acelle/postal">
                        <img class="plugin-icon" src="/p/assets/L2FwcC9wbHVnaW5zL2FjZWxsZS9wb3N0YWwvaWNvbi5zdmc%3D">
                        <h5 class="no-margin text-bold kq_search">
                            GeoLite2 Free Geolocation Data
                        </h5>
                        <span class="">
                        	By Maxmind
                        </span>
                        <br>
                        <span class="text-muted">Version: 2020</span>
                    </td>
                    <td>
                        <span class="text-muted2 list-status pull-left">
                        <span class="label label-flat bg-active">
                        Active
                        </span>
                        </span>
                    </td>
                    <td class="text-right text-nowrap">
                        <a href="http://localhost/postal" class="btn btn-mc_default">
                        Setting
                        </a>
                        <a link-confirm="You're about to disable :number plugin(s) from the list." href="http://localhost/admin/plugins/disable?uids=60ba564ec8740" class="btn btn-mc_primary">
                        Reset
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex align-items-center">
            <div class="num_per_page mr-auto">
            	<p><i>Powered by Acelle</i></p>
            </div>
        </div>
    </div>
@endsection
