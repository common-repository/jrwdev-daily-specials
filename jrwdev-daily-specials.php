<?php

/*
Plugin Name: JRWDEV Daily Specials
Plugin URI: http://wordpress.org/extend/plugins/jrwdev-daily-specials/
Description: This plugin adds a custom post type Daily Specials to your site allowing you to easily feature daily specials from your store in either a widget or on a page or post via shortcode. It requires the advanced custom fields plugin to operate correctly.
Author: Jon Wadsworth
Version: 1.5.2
Author URI: http://jrwdev.com/
*/

function jrwdev_daily_specials_init(){
    define('JRWDEV_DAILYSPECIALS_VERSION', '1.5.2');
    define('DS', DIRECTORY_SEPARATOR);
    define('JRWDEV_DS_PLUGIN_URL', plugin_dir_url( __FILE__ ));
    define('JRWDEV_DS_PLUGIN_DIR', dirname( __FILE__ ));
    define('JRWDEV_DS_INCLUDE_PATH', JRWDEV_DS_PLUGIN_DIR.DS.'includes'.DS);

    require_once(JRWDEV_DS_INCLUDE_PATH.'functions.php');
    require_once(JRWDEV_DS_INCLUDE_PATH.'post-types.php');
    require_once(JRWDEV_DS_INCLUDE_PATH.'register-fields.php');
    require_once(JRWDEV_DS_INCLUDE_PATH.'widget.php');
    require_once(JRWDEV_DS_INCLUDE_PATH.'shortcode.php');
    require_once(JRWDEV_DS_INCLUDE_PATH.'options.php');
}
add_action( 'plugins_loaded', 'jrwdev_daily_specials_init' );

function is_ds_archive_template( $template_path ){

    //Get template name
    $template = basename($template_path);

    //Check if template is taxonomy-event-venue.php
    //Check if template is taxonomy-event-venue-{term-slug}.php
    if( 1 == preg_match('/^archive-daily_specials((-(\S*))?).php/',$template) )
         return true;

    return false;
}

add_filter('archive_template', 'daily_specials_archive_template');
function daily_specials_archive_template( $template ){
    global $post;
    if ( is_post_type_archive( 'daily_specials' ) && !is_ds_archive_template($template) ) {
        $template = JRWDEV_DS_INCLUDE_PATH . '/archive-daily_specials.php';
    }
    return $template;
}

/* ------------------------------------------------------------------
 * CONVERT OLD POST TYPE TO NEW POST TYPE
 * --------------------------------------------------------------- */

$post_ids = get_posts(array('post_per_page' => -1, 'post_type' => 'daily-specials'));
if($post_ids){
    //then update each post
    foreach($post_ids as $p){
        $po = array();
        $po = get_post($p->ID,'ARRAY_A');
        $po['post_type'] = "daily_specials";
        wp_update_post($po);
    }
}

/* ------------------------------------------------------------------
 * CREATE SUBMENU LINK ON PLUGINS PAGE
 * --------------------------------------------------------------- */

function jrwdev_ds_plugin_action_links($links, $file) {

    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    // check to make sure we are on the correct plugin
    if ($file == $this_plugin) {

        // link to what ever you want
        $plugin_links[] = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=daily-specials">'.__('Settings', 'daily-specials').'</a>';

        // add the links to the list of links already there
        foreach($plugin_links as $link) {
            array_unshift($links, $link);
        }
    }

    return $links;
}
add_filter('plugin_action_links', 'jrwdev_ds_plugin_action_links', 10, 2);