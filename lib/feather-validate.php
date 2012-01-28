<?php

/**
	Feather Validation Library

	The contents of this file are subject to the terms of the GNU General
	Public License Version 2.0. You may not use this file except in
	compliance with the license. Any of the license terms and conditions
	can be waived if you get permission from the copyright holder.

	Copyright (c) 2011 Bandit Media
	Jermaine Maree

		@package FeatherValidate
		@version 1.2
**/

//! FeatherValidate
class FeatherValidate extends FeatherBase {

	/**
		Initialize
	**/
	static function init($input) {
		// Get current options
		$valid = self::$option;
		// Get tab
		$tab = $input['tab'];
		unset($input['tab']);
		// Validate tab
		switch ($tab) {
			case 'general':
				$valid = self::general($input,$valid);
				break;
			case 'login':
				$valid = self::login($input,$valid);
				break;
			case 'sidebar':
				$valid = self::sidebar($input,$valid);
				break;
			case 'advanced':
				$valid = self::advanced($input,$valid);
				break;
		}
		// Add settings notice
		add_settings_error('feather_setting_notices',
			'feather-updated',__('Settings saved.','feather'),'updated');
		// Return validated options
		return $valid;
	}

	/**
		General
	**/
	private static function general($input,$valid) {
		// Checkbox options
		$checkboxes = 'auto_feed_links|post_formats|post_thumbnails|'.
			'post_format_aside|post_format_audio|post_format_chat|'.
			'post_format_gallery|post_format_image|post_format_link|'.
			'post_format_quote|post_format_status|post_format_video|'.
			'maintenance';
		// Validate options
		foreach(explode('|',$checkboxes) as $option) {
			// Set input value
			$valid[$option] = isset($input[$option])?'1':'0';
		}
		// Return validated options
		return $valid;
	}

	/**
		Login
	**/
	private static function login($input,$valid) {
		// Login Custom
		$valid['login_custom']=isset($input['login_custom'])?'1':'0';
		// Define url options
		$url_options = array('login_logo','login_logo_url');
		// Loop through url options
		foreach($url_options as $item) {
			$valid[$item]=isset($input[$item])?esc_url($input[$item]):'';
		}
		// Define txt options
		$txt_options = array('login_bg_color','login_link_color',
			'login_link_color_hover','login_css');
		// Loop through options
		foreach($txt_options as $option) {
			$valid[$option]=isset($input[$option])?esc_attr($input[$option]):'';
		}
		// Return validated options
		return $valid;
	}

	/**
		Sidebar
	**/
	private static function sidebar($input,$valid) {
		// Checkbox options
		$checkboxes = 'widget_wp_archives|widget_wp_calendar|widget_wp_categories|'.
			'widget_wp_custom_menu|widget_wp_links|widget_wp_meta|widget_wp_pages|'.
			'widget_wp_recent_comments|widget_wp_recent_posts|widget_wp_rss|'.
			'widget_wp_search|widget_wp_tag_cloud|widget_wp_text';
		// Validate options
		foreach(explode('|',$checkboxes) as $option) {
			// Set input value
			$valid[$option] = isset($input[$option])?'1':'0';
		}
		// Return validated options
		return $valid;
	}

	/**
		Advanced
	**/
	private static function advanced($input,$valid) {
		// Define checkbox options
		$checkboxes = 'l10n.js|feed_links_extra|rsd_link|wlwmanifest_link|'.
			'index_rel_link|parent_post_rel_link|start_post_rel_link|'.
			'adjacent_posts_rel_link_wp_head|wp_shortlink_wp_head|'.
			'commentreply_js';
		// Validate options
		foreach(explode('|',$checkboxes) as $option) {
			$valid[$option]=isset($input[$option])?'1':'0';
		}
		// Return validated options
		return $valid;
	}

}
