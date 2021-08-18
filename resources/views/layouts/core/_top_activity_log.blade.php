@php			
	$iconDir = "";
	if (Auth::user()->customer->getColorScheme() == 'white') {
		$iconDir = "dark/";
	}
@endphp
<li class="nav-item dropdown">
	<a href="javascript:;"
		class="nav-link d-flex align-items-center py-3 lvl-1 dropdown-toggle activities-menu-item middle-bar-element"
		id="content-menu"
	>
		<i class="navbar-icon">
			<img src="{{ url('images/icons/'.$iconDir.'SVG/history.svg') }}" />
		</i>
		<span class="leftbar-item">{{ trans('messages.activities') }}</span>
	</a>
</li>

<script>
	var sidebar;

	$(function() {
		$('.activities-menu-item').on('click', function() {
			var sidebar = new Sidebar();
			if(!sidebar.showed()) {
				sidebar.load({
					url: '{{ action('AccountController@activity') }}'
				});
			} else {
				sidebar.hide();
			}
		});
	});
</script>