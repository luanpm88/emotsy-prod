@extends('layouts.empty')

@section('content')
	{{ $subscribers->onEachSide(5)->appends(request()->input())->links() }}
	<table class="table table-box pml-table table-log mt-10">
        <tbody>
	        <tr>
	            <th width="200px">Subscriber</th>
	            <th>DOB</th>
	            <th>Triggered at</th>
	            <th>Diff</th>
	        </tr>
	        @foreach($subscribers as $subscriber)
	        <tr>
                <td>
                    <span class="no-margin kq_search">
                        {{ $subscriber->email }}
                    </span>
                </td>
                <td>
                    <span class="no-margin kq_search">
                        {{ $subscriber->dob }}
                    </span>
                </td>
                <td>
                    <span class="no-margin kq_search">
                        <a href="{{ action('AutoTrigger@show', [ 'id' => $subscriber->auto_trigger_id ]) }}">{{ is_null($subscriber->trigger_at) ? 'null' : $subscriber->auto_trigger_id  }}</a>
                    </span>
                </td>
                <td>
                    <span class="no-margin kq_search">
                        {{ $subscriber->datediff }}
                    </span>
                </td>
            </tr>
            @endforeach
    	</tbody>
    </table>
@endsection