<?php
/**
 * @package S2 Member Protected Media Uploader
 * @version 1.1
 */
/*
Plugin Name: S2 Member Protected Media Uploader
Plugin URI: 
Description: The pulgins creates an alternate uploader and shortcode generator for the protected dowloads folder. It utilizes the s2Member® Framework ~ membership management for WordPress®.
Author: AFiA Design Ernest Smuga
Version: 1.1
Author URI: http://afiadesign.com
*/

defined( 'ABSPATH' ) or die( 'No direct Access to files.' );

include_once 'pmu_funct.php';
///**** work with http://nacin.com/2011/04/16/wordcamp-seattle/ to fix small best practises issue ****///
if (!defined('PMU_THEME_DIR'))
    define('PMU_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());

if (!defined('PMU_PLUGIN_NAME'))
    define('PMU_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('PMU_PLUGIN_DIR'))
    define('PMU_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . PMU_PLUGIN_NAME);

if (!defined('PMU_PLUGIN_URL'))
    define('PMU_PLUGIN_URL', WP_PLUGIN_URL . '/' . PMU_PLUGIN_NAME);

add_action( 'init', 'PMU_custom_post_type_init' );
register_activation_hook( __FILE__, 'PMU_register_write_flush' );
add_action( 'after_switch_theme', 'PMU_theme_switch_rewrite_flush' );
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'PMU_plugin_action_links' );
add_action('admin_menu', 'PMU_admin_menu_init_func');
add_action('admin_print_styles', 'PMU_admin_print_styles');


add_action( 'wp_ajax_PMU_Upload', 'PMU_Upload_callback' );
add_action( 'wp_ajax_nopriv_PMU_Upload', 'PMU_Upload_no_priv' );


add_action( 'wp_ajax_PMU_create_thumbnail', 'PMU_create_thumbnail_callback' );
add_action( 'wp_ajax_nopriv_PMU_create_thumbnail', 'PMU_create_thumbnail_no_priv' );

add_action( 'wp_ajax_PMU_Delete', 'PMU_Delete' );
add_action( 'wp_ajax_nopriv_PMU_Delete', 'PMU_Delete_no_priv' );

PMU_Delete_no_priv
?>