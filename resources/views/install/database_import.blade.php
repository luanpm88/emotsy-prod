@extends('layouts.install')

@section('title', trans('messages.database'))

@section('page_script')    
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('content')

<h3 class="text-teal-800"><i class="icon-database"></i> {{ trans('messages.database_configuration') }}</h3>

    <h5 class="">
        The settings was successfully configured! Click import button to start importing data to database '{{ $database["database_name"] }}'.
    </h5>
    
    <div class="text-right">                                    
        <a data-wait="{{ trans('messages.button_processing') }}" href="{{ action('InstallController@databaseImport', ['action' => 'import']) }}" class="btn btn-primary">{!! trans('messages.import_database') !!} <i class="icon-arrow-right14 position-right"></i></a>
    </div>

@endsection
