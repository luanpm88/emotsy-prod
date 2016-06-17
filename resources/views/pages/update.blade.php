@extends('layouts.frontend')

@section('title', $list->name . ": " . trans('messages.update_page', ['name' => trans('messages.' . $layout->alias)]))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('tinymce/tinymce.min.js') }}"></script>
        
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
@endsection

@section('page_header')

			@include("lists._header")

@endsection

@section('content')
				
				@include("lists._menu")
				
				<h2 class="text-bold text-teal-800 mb-10"><i class="icon-certificate position-left"></i> {{ trans('messages.update_page') }}</h2>
				
                <h3>{{ trans('messages.' . $layout->alias) }}</h3>
                
                @if ($layout->alias == 'sign_up_form')
                    <p class="alert alert-info mt-20 mb-20">{{ trans('messages.sign_up_form_url') }}<br /> <a href="" class="text-semibold">{{ action('PageController@update', ['list' => $list->uid, 'alias' => $layout->alias]) }}</a></p>
                @endif
                    
                <form action="{{ action('PageController@update', ['list' => $list->uid, 'alias' => $layout->alias]) }}" method="POST" class="form-validate-jqueryz">
					{{ csrf_field() }}
                    
                    @include('helpers.form_control', ['type' => 'textarea', 'name' => 'content', 'value' => $page->content, 'rules' => $list->getFieldRules()])
                    
                    <div class="">
                        <button type="submit" class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.save_change') }}</button>
                    </div>
                </form>
                
                
@endsection
