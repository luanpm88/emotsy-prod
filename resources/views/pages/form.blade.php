@extends('layouts.core.page')

@section('title', $page->subject)

@section('content')

	<form action="" method="POST" class="form-validate-jqueryz">
		{{ csrf_field() }}
		
		{!! $page->content !!}
	
	</form>
		
@endsection