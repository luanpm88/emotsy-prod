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
			wp_die(
				'<h1>' . __( 'Something went wrong.' ) . '</h1>' .
				'<p>' . __( 'The requested theme does not exist.' ) . '</p>',
				403
			);
		}

		switch_theme( $theme->get_stylesheet() );
    }
}