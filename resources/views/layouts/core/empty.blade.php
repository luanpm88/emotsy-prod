<!DOCTYPE html>
<html lang="en">
<head>
	@include('layouts.core._head')

	<script>
		$.cookie('last_language_code', '{{ Auth::user()->customer->getLanguageCode() }}');
	</script>

</head>

<body>
    @yield('content')
</body>
</html>
