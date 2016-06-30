<?php
/*
Plugin Name: Simple Photo Gallery
Version: v1.8.1
Plugin URI: http://photography-solutions.tipsandtricks-hq.com/simple-wordpress-photo-gallery-plugin
Author: Tips and Tricks HQ, Peter Petreski
Author URI: http://www.tipsandtricks-hq.com/
Description: A simple and user-friendly photo gallery plugin for your WordPress web site.
License: GPL3
*/

if(!defined('ABSPATH'))exit; //Exit if accessed directly

include_once('wppg-photo-gallery-core.php');
register_activation_hook(__FILE__,array('WP_Photo_Gallery','activate_handler'));//activation hook
//register_deactivation_hook(__FILE__,array('WP_Photo_Gallery_','deactivate_handler'));//deactivation hook

function wppg_show_plugin_settings_link($links, $file) 
{
    if ($file == plugin_basename(__FILE__)){
            $settings_link = '<a href="admin.php?page=wppg_gallery">Settings</a>';
            array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'wppg_show_plugin_settings_link', 10, 2 );

