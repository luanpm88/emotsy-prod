<?php

namespace Acelle\Library;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class WordpressManager
{
    public function __construct()
    {
        require_once config('wordpress.1.path') . '/wp-load.php';
        require_once ABSPATH . 'wp-admin/includes/admin.php';
    }

    public function getTemplates()
    {
        return wp_prepare_themes_for_js();
    }

    public function activateTheme($id)
    {
        $theme = wp_get_theme($id);

        if (! $theme->exists() || ! $theme->is_allowed()) {
            throw new \Exception('The requested theme does not exist.');
        }

        switch_theme($theme->get_stylesheet());
    }

    public function getProductCategories()
    {
        $taxonomy     = 'product_cat';
        $orderby      = 'name';
        $hierarchical = 1;
        $empty        = 0;


        $args = array(
            'taxonomy'     => $taxonomy,
            'orderby'      => $orderby,
            'hierarchical' => $hierarchical,
            'hide_empty'   => $empty,
        );

        $all_categories = get_categories($args);

        $data = [];
        foreach ($all_categories as $cat) {
            $data[] = [
                'value' => $cat->term_id,
                'text' => $cat->name,
            ];
        }

        return $data;
    }
}
