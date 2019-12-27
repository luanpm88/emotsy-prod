@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <h3 class="mb-3">{{ trans('messages.automation.profile.tag_contacts') }}</h3>
            <p>{!! trans('messages.automation.profile.tag_contacts.intro', [
                'count' => '1,200',
            ]) !!}</p>
                
            @include('helpers.form_control', [
                'type' => 'select',
                'class' => '',
                'label' => 'Select criterion',
                'name' => 'type',
                'value' => ['VIP customer', 'Lead'],
                'help_class' => 'trigger',
                'options' => [
                    ['text' => 'Hight profile', 'value' => 'Hight profile'],
                    ['text' => 'VIP customer', 'value' => 'VIP customer'],
                    ['text' => 'Lead', 'value' => 'Lead'],
                ],
                'rules' => [],
                'multiple' => 'true',
            ])

            <div class="mt-4">
                <button class="btn btn-secondary">{{ trans('messages.automation.profile.tag') }}</button>
            </div>
        </div>
    </div>
@endsection
