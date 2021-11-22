<?php

namespace Acelle\Library;

class WordpressManager {
    public function __construct()
    {
        require_once config('wordpress.1.path') . '/wp-load.php';
        require_once ABSPATH . 'wp-admin/includes/admin.php';
    }

    public function activateTheme($id)
    {
		$theme = wp_get_theme( $id );

		if ( ! $theme->exists() || ! $theme->is_allowed() ) {
			throw new \Exception('The requested theme does not exist.');
		}

		switch_theme( $theme->get_stylesheet() );
    }
}