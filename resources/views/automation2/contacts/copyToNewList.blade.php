@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <h3 class="mb-3">{{ trans('messages.automation.contacts.copy_to_new_list') }}</h3>
            <p>{!! trans('messages.automation.contacts.copy_to_new_list.intro', [
                'count' => '1,200',
            ]) !!}</p>
                
            @include('helpers.form_control', [
                'type' => 'text',
                'class' => '',
                'label' => '',
                'name' => 'type',
                'value' => '',
                'placeholder' => trans('messages.automation.contacts.copy_to_new_list.enter_list_name'),
                'help_class' => 'trigger',
                'rules' => [],
            ])

            <div class="mt-4">
                <button class="btn btn-secondary">{{ trans('messages.automation.contacts.copy_to_new_list.copy') }}</button>
            </div>
        </div>
    </div>
@endsection
