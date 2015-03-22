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

if (!defined('PMU_THEME_DIR'))
    define('PMU_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());

if (!defined('PMU_PLUGIN_NAME'))
    define('PMU_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('PMU_PLUGIN_DIR'))
    define('PMU_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . PMU_PLUGIN_NAME);

if (!defined('PMU_PLUGIN_URL'))
    define('PMU_PLUGIN_URL', WP_PLUGIN_URL . '/' . PMU_PLUGIN_NAME);

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'PMU_plugin_action_links' );
add_action('admin_menu', 'PMU_admin_menu_init_func');
add_action('admin_print_styles', 'PMU_admin_print_styles');





?>