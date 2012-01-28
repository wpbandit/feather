<?php

/**
	Feather Admin Library

	The contents of this file are subject to the terms of the GNU General
	Public License Version 2.0. You may not use this file except in
	compliance with the license. Any of the license terms and conditions
	can be waived if you get permission from the copyright holder.

	Copyright (c) 2011 Bandit Media
	Jermaine Maree

		@package FeatherAdmin
		@version 1.2.1
**/

class FeatherAdmin extends FeatherBase {

	protected static
		//! Plugin Hook
		$hook;

	/**
		Admin methods and actions
			@public
	**/
	static function boot() {
		// Admin init
		add_action('admin_init',__CLASS__.'::init');
		// Admin notices
		add_action('admin_notices',__CLASS__.'::notices');
		// Admin menu
		add_action('admin_menu',__CLASS__.'::menu');
		// Meta boxes
		if(!self::$vars['DEPRECATED']) {
			self::meta();
		} else {
			self::meta_deprecated();
		}
		
	}

	/**
		Admin methods and actions
			@public
	**/
	static function init() {
		// Set Hook
		self::$hook = get_plugin_page_hook('feather','options-general.php');
		// Register setting
		register_setting('feather-settings','feather','FeatherValidate::init');
		// Load validation library
		require(FEATHER_PATH.'lib/feather-validate.php');
		// Plugin-specific actions
		add_action('load-'.self::$hook,__CLASS__.'::feather_init');
		// Plugin styles
		add_action('admin_print_styles-'.self::$hook,__CLASS__.'::styles');
		// Plugin scripts
		add_action('admin_print_scripts-'.self::$hook,__CLASS__.'::scripts');
	}

	/**
		Admin notice
			@public
	**/
	static function notices() {
		// Framework notices
		if(self::$notices) {
			$output = '';
			foreach(self::$notices as $notice) {
				// Sanitation
				$class = esc_attr($notice['class']);
				$message = esc_attr($notice['message']);
				// HTML
				$output .= '<div id="message" class="'.$class.'">'.
					'<p>'.$message.'</p></div>';
			}
			echo $output;
		}
		// Setting notices
		settings_errors('feather_setting_notices');
	}

	/**
		Admin menu
			@public
	**/
	static function menu() {
		// Add page to the Settings menu
		add_options_page('Feather','Feather','manage_options',
			'feather',__CLASS__.'::options_page');
	}

	/**
		Options page
			@public
	**/
	static function options_page() {
		$tabs = self::$vars['TABS'];
		$current_tab = self::get_current_tab($tabs);
		require(FEATHER_PATH.'tmpl/feather-options-page.php');
	}

	/**
		Meta
	**/
	private static function meta() {
		if(self::get_config('CUSTOM_META')) {
			$pages = array('post.php','post-new.php');
			if(in_array(self::$vars['PAGENOW'],$pages)) {
				// Load meta library
				require(FEATHER_PATH.'lib/feather-meta.php');
				// Initialize settings library
				FeatherMeta::init();
			}	
		}
	}

	/**
		Feather settings page init
			@public
	**/
	static function feather_init() {
		// Load settings library
		require(FEATHER_PATH.'lib/feather-settings.php');
		// Feather settings
		$settings = FeatherConfig::load('settings','setting',TRUE);
		// Initialize settings library
		FeatherSettings::init('feather',$settings);
	}

	/**
		Scripts
			@public
	**/
	static function scripts() {
		// Javascript URL
		$url = FEATHER_URL.'assets/js/';
		// Javascript Path
		$path = FEATHER_PATH.'assets/js/';
		// Register colorpicker.js
		wp_register_script('feather-color-js',$url.'colorpicker.js',array('jquery'));
		// feather.js Dependencies
		$js_dep = array('media-upload','thickbox','jquery','feather-color-js');
		// feather.js Version
		$js_ver = filemtime($path.'feather.js');
		// Enqueue feather.js script
		wp_enqueue_script('feather-js',$url.'feather.js',$js_dep,$js_ver);
	}

	/**
		Styles
			@public
	**/
	static function styles() {
		// CSS URL
		$url = FEATHER_URL.'assets/css/';
		// CSS Path
		$path = FEATHER_PATH.'assets/css/';
		// Register colorpicker.css
		wp_register_style('feather-color-css',$url.'colorpicker.css',FALSE);
		// feather.css Dependencies
		$css_dep = array('thickbox','feather-color-css');
		// feather.css Version
		$css_ver = filemtime($path.'feather.css');
		// Enqueue style
		wp_enqueue_style('feather-css',$url.'feather.css',$css_dep,$css_ver);
	}

	/**
		Get current tab
			@param $tabs array
			@public
	**/
	static function get_current_tab($tabs) {
		reset($tabs);
		$current_tab = isset($_GET['tab'])?esc_attr($_GET['tab']):key($tabs);
		return $current_tab;
	}

	/**
		Print tabs
			@param $tabs array
			@public
	**/
	static function print_tabs($tabs,$page='feather') {
		// Current tab
		$current = self::get_current_tab($tabs);
		// Build tabs
		if(function_exists('get_screen_icon')) {
			$output = get_screen_icon().'<h2 class="nav-tab-wrapper">';
		} else {
			$output = '<div id="icon-themes" class="icon32"><br /></div>'.
				'<h2 class="nav-tab-wrapper">';
		}
		// Create links
		foreach($tabs as $tab=>$name) {
			$attrs = array(
				'class' => ($tab==$current)?'nav-tab nav-tab-active':'nav-tab',
				'href'	=> '?page='.$page.'&tab='.$tab
			);
			$output .= '<a'.self::attributes($attrs).'>'.$name.'</a>';
		}
		$output .= '</h2>';
		// Output HTML
		echo $output;
	}

	/* DEPRECATED FUNCTIONS
	/*-----------------------------------------------------------------------*/

	/**
		Meta *** DEPRECATED
	**/
	private static function meta_deprecated() {
		// Theme Meta
		if(in_array(self::$vars['PAGENOW'],array('post.php','post-new.php'))) {
			self::$theme_meta = FeatherConfig::theme('config-meta','meta');
			if(self::$theme_meta) {
				// Load Meta library
				require(FEATHER_PATH.'lib/feather-form.php');
				require(FEATHER_PATH.'lib/_deprecated/feather-meta.php');
				// Add action to add meta boxes
				add_action('add_meta_boxes','FeatherMeta::init');
				// Add action to save meta
				add_action('save_post','FeatherMeta::save_meta');
			}
		}
	}

	/**
		Print options page tabs *** DEPRECATED ***
	**/
	static function print_options_page_tabs($page='theme') {
		// Set tabs
		switch($page) {
			case 'feather':
				$tabs=self::$vars['TABS'];
				break;
			case 'theme':
				$tabs=self::$config['OPTION_TABS'];
				break;
		}
		// Get current tab
		$current=self::get_current_options_page_tab($page);
		// Build tab links
		$links=array();
		foreach($tabs as $tab=>$name) {
			$active=($tab==$current)?' nav-tab-active':'';
			$links[]='<a class="nav-tab'.$active.'" href="?page=feather&tab='.
				$tab.'">'.$name.'</a>';
		}
		// Create html for tabs
		if(function_exists('get_screen_icon')) {
			$output=get_screen_icon().'<h2 class="nav-tab-wrapper">';
		} else {
			$output='<div id="icon-themes" class="icon32"><br /></div>'.
				'<h2 class="nav-tab-wrapper">';
		}
		// Add tab links to output
		foreach($links as $link)
			$output.=$link;
		$output.='</h2>';
		// Output html
		echo $output;
	}

	/**
		Get current options page tab *** DEPRECATED ***
	**/
	static function get_current_options_page_tab($page='theme') {
		// Set tabs
		switch($page) {
			case 'feather':
				$tabs=self::$vars['TABS'];
				break;
			case 'theme':
				$tabs=self::$config['OPTION_TABS'];
				break;
		}
		reset($tabs);
		$tab=isset($_GET['tab'])?esc_attr($_GET['tab']):key($tabs);
		return $tab;
	}

	/**
		Process settings *** DEPRECATED ***
	**/
	static function process_settings(array $settings,$option,$optionfunc=NULL) {
		$page = isset($_GET['page'])?esc_attr($_GET['page']):FALSE;
		if($page && ($page=='feather')) {
			require(FEATHER_PATH.'lib/feather-form.php');
			require(FEATHER_PATH.'lib/_deprecated/feather-settings.php');
			if(!$optionfunc) {
				$optionfunc = 'FeatherBase::get_theme_option';
			}
			// Add settings sections
			foreach($settings as $id=>$section) {
				// Set section id
				$section['id']=self::$prefix.'_'.$id;
				// Set section callback
				$section['callback']=$id;
				// Add section
				FeatherSettings::add_section($section,$option);
				// Add settings fields
				foreach($section['fields'] as $fid=>$field) {
					// Set field id
					$field['id']=$fid;
					// Set field tab
					$field['tab']=$section['tab'];
					// Set field setting
					$field['setting']=$option;
					// Set field section
					$field['section']=$section['id'];
					// Set option function
					if(isset($optionfunc))
						$field['optionfunc']=$optionfunc;
					// Add field
					FeatherSettings::add_field($field,$option);	
				}
			}
		}
	}

	/* END DEPRECATED FUNCTIONS
	/*-----------------------------------------------------------------------*/

}
