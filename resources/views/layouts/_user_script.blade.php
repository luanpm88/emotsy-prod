<script>
	$.cookie('last_language_code', '{{ Auth::user()->customer->getLanguageCode() }}');
</script>