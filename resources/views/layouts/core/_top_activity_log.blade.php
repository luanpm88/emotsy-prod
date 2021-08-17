@php			
	$iconDir = "";
	if (Auth::user()->customer->getColorScheme() == 'white') {
		$iconDir = "dark/";
	}
@endphp
<li class="nav-item dropdown">
	<a href="javascript:;"
		class="nav-link d-flex align-items-center py-3 lvl-1 dropdown-toggle activities-menu-item"
		id="content-menu"
	>
		<i class="navbar-icon">
			<img src="{{ url('images/icons/'.$iconDir.'SVG/history.svg') }}" />
		</i>
		<span class="leftbar-item">{{ trans('messages.activities') }}</span>
	</a>
</li>

<script>
	var activityBox;

	$(function() {
		$('.activities-menu-item').on('click', function() {
		openMiddleBar();

			activityBox = new Box($('.middle-bar .content'), '{{ action('AccountController@activity') }}');

			activityBox.load();
		});
	});
</script>