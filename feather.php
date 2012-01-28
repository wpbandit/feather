<?php
/*
Plugin Name: Feather
Plugin URI: http://banditmedia.github.com/feather/
Description: Lightweight & Powerful Theme Framework
Version: 1.2.5
Author: Jermaine Marée of WPBandit
Author URI: http://wpbandit.com
*/

//! Define constants
define('FEATHER_URL',plugin_dir_url(__FILE__));
define('FEATHER_PATH',plugin_dir_path(__FILE__));
define('FEATHER_THEME_URL',get_template_directory_uri().'/feather/');
define('FEATHER_THEME_PATH',get_template_directory().'/feather/');

//! *** DEPRECATED CONSTANTS ***
define('FEATHER_URL_THEME',get_template_directory_uri().'/feather/');
define('FEATHER_PATH_THEME',get_template_directory().'/feather/');

// Add action to load framework
add_action('setup_theme','load_feather');

//! Load Feather
function load_feather() {
	// Verify feather directory exists
	if(!is_dir(FEATHER_THEME_PATH)) {
		// Display admin error
		if(is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {
			add_action('admin_notices','feather_admin_notice_compat');
		}
		return;
	}
	// Load framework
	require(FEATHER_PATH.'lib/feather-base.php');
}

//! Compat Admin Notice
function feather_admin_notice_compat() {
	// Compatibility message
	$message = __('Feather : The current theme is incompatible. Activate '.
		'a compatible theme or disable the plugin.','feather');
	// Admin notice
	$output = '<div class="error fade"><p>'.$message.'</p></div>';
	// Display notice
	echo $output;
}
