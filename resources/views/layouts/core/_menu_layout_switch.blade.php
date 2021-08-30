<div>
    <label class="layout-select me-3">
        <input type="radio" name="menu_layout" value="top" {{ $menu_layout == 'top' || $menu_layout == 'none' ? 'checked' : '' }} class="styled mr-2" />
        <img src="{{ url('images/layout-menu-top.svg') }}" width="150px" />
    </label>
    <label class="layout-select">
        <input type="radio" name="menu_layout" value="left" {{ $menu_layout == 'left' ? 'checked' : '' }} class="styled mr-2"  />
        <img src="{{ url('images/layout-menu-left.svg') }}" width="150px" />
    </label>
</div>

<script>
    $(function() {
        $('[name=menu_layout]').on('change', function(e) {
            var type = $('[name=menu_layout]:checked').val();

            // reset classes
            $('body').removeClass('topbar');
            $('body').removeClass('leftbar');
            $('body').removeClass('leftbar-closed');
            $('.navbar-main').css('margin-top', '');

            $('body').addClass(type + 'bar');
        });
    })
</script>