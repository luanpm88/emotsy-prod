@foreach ($notifications as $n)
    @include('elements._notification', [
        'level' => $n->level,
        'title' => $n->title,
        'message' => $n->message]
    )
@endforeach
