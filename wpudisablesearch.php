<?php
/*
Plugin Name: WPU disable search
Plugin URI: https://github.com/WordPressUtilities/wpudisablesearch
Description: Disable search
Version: 0.3.0
Author: Darklg
Author URI: http://darklg.me/
License: MIT License
License URI: http://opensource.org/licenses/MIT
Thanks: http://www.geekpress.fr/?p=722
*/

/* ----------------------------------------------------------
  Prevent query
---------------------------------------------------------- */

function wpu_disable_search__in_query($query, $error = true) {
    if (!is_search()) {
        return;
    }
    $query->is_search = false;
    $query->query_vars['s'] = false;
    $query->query['s'] = false;
    // to error
    if ($error) {
        $query->is_404 = true;
    }
}

add_action('init', 'wpu_disable_search__init');
function wpu_disable_search__init() {
    if (is_admin()) {
        return;
    }
    add_action('parse_query', 'wpu_disable_search__in_query');
    add_filter('get_search_form', '__return_null');
}

/* ----------------------------------------------------------
  Remove from WP API
---------------------------------------------------------- */

add_filter('rest_endpoints', 'wpu_disable_search_rest_endpoints');
function wpu_disable_search_rest_endpoints($endpoints) {
    $endpoints_keys = array(
        '/wp/v2/search'
    );

    foreach ($endpoints_keys as $key) {
        if (isset($endpoints[$key])) {
            unset($endpoints[$key]);
        }
    }

    return $endpoints;
}
