<ul class="nav nav-tabs nav-tabs-top nav-underline mb-4">
    <li class="nav-item {{ $menu == 'general' ? 'active' : '' }}">
        <a href="{{ action('SiteController@create', [
            'id' => 1,
        ]) }}" class="nav-link">
            <span class="d-flex align-items-center">
                <span class="material-symbols-rounded me-2">
                    border_color
                </span>
                <span>General</span>
            </span>
        </a>
    </li>
    <li class="nav-item {{ $menu == 'content' ? 'active' : '' }}">
        <a href="{{ action('SiteController@content', [
            'id' => 1,
        ]) }}" class="nav-link">
            <span class="d-flex align-items-center">
                <span class="material-symbols-rounded me-2">
                    newspaper
                </span>
                <span>Content</span>
            </span>
        </a>
    </li>
    <li class="nav-item {{ $menu == 'settings' ? 'active' : '' }}">
        <a href="{{ action('SiteController@settings', [
            'id' => 1,
        ]) }}" class="nav-link">
            <span class="d-flex align-items-center">
                <span class="material-symbols-rounded me-2">
                    tune
                </span>
                <span>Settings</span>
            </span>
        </a>
    </li>
</ul>