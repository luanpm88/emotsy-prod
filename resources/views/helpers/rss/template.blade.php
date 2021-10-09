<h1>{{ $title }}</h1>
<p>Published date: {{ $pubDate }}</p>

@foreach ($records as $r)

    <p><strong>{{ $r['title'] }}</strong></p>
    <p>{{ array_key_exists('description', $r) ? $r['description'] : null }}</p>
    <p>{{ array_key_exists('pubDate', $r) ? $r['pubDate'] : null }}</p>
    <hr>

@endforeach
