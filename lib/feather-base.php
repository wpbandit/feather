<?php

/**
	Feather WordPress Theme Framework

	The contents of this file are subject to the terms of the GNU General
	Public License Version 2.0. You may not use this file except in
	compliance with the license. Any of the license terms and conditions
	can be waived if you get permission from the copyright holder.

	Copyright (c) 2011 Bandit Media
	Jermaine Maree

		@package FeatherBase
		@version 1.2.5
**/

//! Base structure
class FeatherBase {

	//@{ Framework details
	const
		TEXT_Framework='Feather',
		TEXT_Version='1.2.5';
	//@}

	//@{ Locale-specific error/exception messages
	const
		TEXT_Config='The configuration file %s was not found',
		TEXT_File='The required file %s was not found',
		TEXT_Widget='The widget file %s was not found',
		TEXT_Method='Undefined method %s';
	//@}

	protected static
		//! Global variables
		$vars,
		//! Framework prefix
		$prefix='feather',
		//! Configuration
		$config,
		//! Framework Settings
		$setting,
		//! Options
		$option,
		//! Notices
		$notices;

	//! *** Deprecated Variables ***
	protected static
		//! Theme options
		$theme_option,
		//! Theme settings
		$theme_setting,
		//! Theme meta
		$theme_meta;

	/**
		Compiles an array of HTML attributes into an attribute string
			@return string
			@param $attrs array
			@protected
	**/
	protected static function attributes(array $attrs) {
		if(!empty($attrs)) {
			$result='';
			foreach($attrs as $key=>$val)
				$result.=' '.$key.'="'.$val.'"';
			return $result;
		}
	}
	/**
		Get configuration option
			@param $key string
			@protected
	**/
	protected static function get_config($key) {
		$value = isset(self::$config[$key])?self::$config[$key]:FALSE;
		return $value;
	}

	/**
		Get framework option
			return mixed
			@param $key string
			@public
	**/
	static function get_option($key) {
		$value = isset(self::$option[$key])?self::$option[$key]:FALSE;
		return $value;
	}

	/**
		Add notice
			@param $message string
			@param $class string
			@protected
	**/
	protected static function add_notice($message,$class='updated') {
		if(!self::$notices) { self::$notices = array(); }
		self::$notices[] = array(
			'class'		=> $class,
			'message'	=> $message
		);
	}

	/**
		Prevent class instantiation
			@private
	**/
	private function __construct() {}

	/**
		Prevent cloning
			@private
	**/
	private function __clone() {}

	/* DEPRECATED FUNCTIONS
	/*-----------------------------------------------------------------------*/

	/**
		Get theme option
			@param $name string
			@public
	**/
	static function get_theme_option($name=NULL) {
		$result=isset(self::$theme_option[$name])?self::$theme_option[$name]:FALSE;
		return $result;
	}

	/**
		Load theme file
			@param $name string
			@param $dir string
			@protected
	**/
	protected static function load_theme_file($name=NULL,$dir='') {
		$file = FEATHER_PATH_THEME.$dir.'/'.$name.'.php';
		if(is_file($file)) {
			require($file);
			return TRUE;
		}
	}

	/* END DEPRECATED FUNCTIONS
	/*-----------------------------------------------------------------------*/

}

//! Config file loader
class FeatherConfig extends FeatherBase {

	/**
		Load configuration file
			@return array
			@param $name string
			@param $var string
			$param $_internal bool
			@public
	**/
	static function load($name,$var,$_internal=FALSE) {
		// Theme
		if(!$_internal) {
			if(is_file(FEATHER_THEME_PATH.'config/config-'.$name.'.php')) {
				ob_start();
				require(FEATHER_THEME_PATH.'config/config-'.$name.'.php');
				ob_end_clean();
			}
		}
		// Internal
		if($_internal) {
			if(is_file(FEATHER_PATH.'config/feather-config-'.$name.'.php')) {
				ob_start();
				require(FEATHER_PATH.'config/feather-config-'.$name.'.php');
				ob_end_clean();
			}
		}
		// Return variable
		return isset($$var)?$$var:FALSE;
	}

	/* DEPRECATED FUNCTIONS
	/*-----------------------------------------------------------------------*/

	/**
		Theme config file
			@param $name string
			@protected
	**/
	static function theme($name=NULL,$var=NULL) {
		// File path
		$file = FEATHER_PATH_THEME.'config/'.$name.'.php';
		if(!is_file($file)) {
			return FALSE;
		} else {
			ob_start();
			require($file);
			ob_end_clean();
		}
		// Return config
		return isset($$var)?$$var:FALSE;
	}

	/* END DEPRECATED FUNCTIONS
	/*-----------------------------------------------------------------------*/

}

//! Feather Framework
class FeatherCore extends FeatherBase {

	/**
		Start framework
			@public
	**/
	static function boot() {
		// Prevent multiple calls
		if(self::$vars)
			return;
		// Initialize framework
		self::init();
		// Theme initialization
		if(method_exists('FeatherTheme','init')) {
			FeatherTheme::init();
		}
		// Modules
		self::modules();
		// Taxonomies
		self::taxonomies();
		// Post types
		self::post_types();
		// Menus
		self::menus();
		// Sidebars
		self::sidebars();
		// Widgets
		self::widgets();
		// Theme features
		add_action('after_setup_theme',__CLASS__.'::theme_features');
		// Register scripts
		add_action('wp_enqueue_scripts',__CLASS__.'::register_scripts');
		// Boot admin
		if(is_admin()) {
			require(FEATHER_PATH.'lib/feather-admin.php');
			FeatherAdmin::boot();
		}
		// Non-admin configuration
		if(!is_admin()) {
			// Add action for theme head
			add_action('template_redirect',__CLASS__.'::theme_head');
		}
	}

	/**
		Initialize framework
			@private
	**/
	private static function init() {
		// Global $pagenow variable
		global $pagenow;
		// Permalinks
		$permalinks = get_option('permalink_structure');
		// Hydrate framework variables
		self::$vars=array(
			// Global $pagenow variable
			'PAGENOW' => $pagenow,
			// Permalinks
			'PERMALINKS' => ($permalinks && ($permalinks != ''))?TRUE:FALSE,
			// Version
			'VERSION' => self::TEXT_Framework.' '.self::TEXT_Version,
			// Deprecated
			'DEPRECATED' => FALSE,
			// Options Page Tabs
			'TABS' => array(
				'general'	=> __('General','feather'),
				'sidebar'	=> __('Sidebar','feather'),
				'login'		=> __('Login','feather'),
				'advanced'	=> __('Advanced','feather')
			),
			// WP Version
			'WP_VERSION'	=> get_bloginfo('version'),
			// WP Post Formats
			'WP_POSTFORMATS'=>'aside|audio|chat|gallery|image|link|'.
				'quote|status|video',
			// Default WP Widgets
			'WP_WIDGETS' => array(
				'widget_wp_archives'		=> 'WP_Widget_Archives',
				'widget_wp_calendar'		=> 'WP_Widget_Calendar',
				'widget_wp_categories'		=> 'WP_Widget_Categories',
				'widget_wp_custom_menu'		=> 'WP_Nav_Menu_Widget',
				'widget_wp_links'			=> 'WP_Widget_Links',
				'widget_wp_meta'			=> 'WP_Widget_Meta',
				'widget_wp_pages'			=> 'WP_Widget_Pages',
				'widget_wp_recent_comments'	=> 'WP_Widget_Recent_Comments',
				'widget_wp_recent_posts'	=> 'WP_Widget_Recent_Posts',
				'widget_wp_rss'				=> 'WP_Widget_RSS',
				'widget_wp_search'			=> 'WP_Widget_Search',
				'widget_wp_tag_cloud'		=> 'WP_Widget_Tag_Cloud',
				'widget_wp_text'			=> 'WP_Widget_Text'
			)
		);
		// Get feather option
		self::$option = get_option('feather');
		// If no options, set defaults
		if(!self::$option || !is_array(self::$option)) {
			self::$option = array('version' => self::TEXT_Version);
			update_option('feather',self::$option);
		}
		// Upgrade, if necessary
		if(is_admin()) { self::upgrade(); }
		// Custom login page
		self::login();
		// Maintenance page
		self::maintenance();
		// Load theme library
		if(is_file(FEATHER_THEME_PATH.'lib/feather-theme.php')) {
			require(FEATHER_THEME_PATH.'lib/feather-theme.php');
		}
		// Load configuration file
		if(is_file(FEATHER_THEME_PATH.'config/feather-config.php')) {
			// Deprecated version of framework
			self::$vars['DEPRECATED'] = TRUE;
			// Load config file
			ob_start();
			require(FEATHER_THEME_PATH.'config/feather-config.php');
			ob_end_clean();
			// Set $config variable
			self::$config = isset($config)?$config:FALSE;
			if(self::get_config('OPTION_NAME')) {
				self::$theme_option=get_option(self::$config['OPTION_NAME']);
			}
		} else {
			self::$config = FeatherConfig::load('feather','config');
		}
		
	}

	/**
		Upgrade
			@private
	**/
	private static function upgrade() {
		$current_version = self::get_option('version');
		// Do we need to upgrade?
		if(version_compare($current_version,self::TEXT_Version) < 0) {
			require(FEATHER_PATH.'lib/feather-upgrade.php');
			FeatherUpgrade::init();
		}
	}

	/**
		Modules
			@private
	**/
	private static function modules() {
		$modules = self::get_config('MODULES');
		$modules_path = FEATHER_THEME_PATH.'modules/';
		if($modules) {
			foreach($modules as $name=>$class) {
				// Full path to module
				if(!self::$vars['DEPRECATED']) {
					$module = $modules_path.$name.'/'.$name.'.php';
				} else {
					$module = $modules_path.'/'.$name.'.php';
				}
				// Load module
				if(is_file($module)) {
					require($module);
					call_user_func(array($class,'init'));
				}
			}
		}
	}

	/**
		Taxonomies
			@private
	**/
	private static function taxonomies() {
		if(self::get_config('CUSTOM_TAXONOMY')) {
			$taxonomies = FeatherConfig::load('taxonomy','tax');
			if($taxonomies) {
				foreach($taxonomies as $name=>$taxonomy) {
					if(!isset($taxonomy['type'])) { $taxonomy['type'] = NULL; }
					if(!isset($taxonomy['args'])) { $taxonomy['args'] = NULL; }
					register_taxonomy($name,$taxonomy['type'],$taxonomy['args']);
				}	
			}
		}
	}

	/**
		Post types
			@private
	**/
	private static function post_types() {
		if(self::get_config('CUSTOM_TYPE')) {
			$types = FeatherConfig::load('type','type');
			if($types) {
				foreach($types as $type=>$args) {
					register_post_type($type,$args);
				}	
			}
		}
	}

	/** 
		Menus
			@private
	**/
	private static function menus() {
		$menus = self::get_config('MENUS');
		if($menus) { register_nav_menus($menus); }
	}

	/**
		Sidebars
			@private
	**/
	private static function sidebars() {
		$sidebars = self::get_config('SIDEBARS');
		if($sidebars) {
			foreach($sidebars as $sidebar) {
				// Single Sidebar
				if(!isset($sidebar['count'])) {
					register_sidebar($sidebar);
				}
				// Multiple Sidebars
				if(isset($sidebar['count'])) {
					$count = $sidebar['count'];
					unset($sidebar['count']);
					register_sidebars($count,$sidebar);
				}
			}
		}
	}

	/**
		Widgets
			@private
	**/
	private static function widgets() {
		$widgets = self::get_config('WIDGETS');
		$widget_path = get_template_directory().'/widgets/';
		if($widgets) {
			foreach($widgets as $name=>$class) {
				if(is_file($widget_path.$name.'.php')) {
					require($widget_path.$name.'.php');
					$function = 'return register_widget("'.$class.'");';
					add_action('widgets_init',create_function('',$function));
				}
			}
		}
		// Add action to unregister widgets
		add_action('widgets_init',__CLASS__.'::unregister_default_widgets',1);
	}

	/**
		Unregister default WP widgets
			@public
	**/
	static function unregister_default_widgets() {
		foreach(self::$vars['WP_WIDGETS'] as $id=>$class)
			if(self::get_option($id))
				unregister_widget($class);
	}

	/**
		Theme features
			@public
	**/
	static function theme_features() {
		// Automatic Feed Links
		if(self::get_option('auto_feed_links'))
			add_theme_support('automatic-feed-links');
		// Post Thumbnails
		if(self::get_option('post_thumbnails')) {
			add_theme_support('post-thumbnails');
			// Register image sizes
			if(self::get_config('IMAGE_SIZES')) {
				foreach(self::$config['IMAGE_SIZES'] as $name=>$image) {
					if(!isset($image['crop'])) { $image['crop']=FALSE; }
					add_image_size($name,$image['width'],$image['height'],$image['crop']);
				}
			}
		}
		// Post Formats
		if(self::get_option('post_formats')) {
			$post_formats=array();
			foreach(explode('|',self::$vars['WP_POSTFORMATS']) as $format)
				if(self::get_option('post_format_'.$format))
					$post_formats[]=$format;
			if(!empty($post_formats))
				add_theme_support('post-formats',$post_formats);
		}
	}

	/**
		Theme head
			@public
	**/
	static function theme_head() {
		// Comment Reply JavaScript
		if(!self::get_option('commentreply_js'))
			if(is_singular())
				wp_enqueue_script('comment-reply');
		// Remove items from head
		if(self::get_option('l10n.js'))
			wp_deregister_script('l10n');
		if(self::get_option('feed_links_extra'))
			remove_action('wp_head','feed_links_extra',3);
		if(self::get_option('rsd_link'))
			remove_action('wp_head','rsd_link');
		if(self::get_option('wlwmanifest_link'))
			remove_action('wp_head','wlwmanifest_link');
		if(self::get_option('index_rel_link'))
			remove_action('wp_head','index_rel_link');
		if(self::get_option('parent_post_rel_link'))
			remove_action('wp_head','parent_post_rel_link',10,0);
		if(self::get_option('start_post_rel_link'))
			remove_action('wp_head','start_post_rel_link',10,0);
		if(self::get_option('adjacent_posts_rel_link_wp_head'))
			remove_action('wp_head','adjacent_posts_rel_link_wp_head',10,0);
		if(self::get_option('wp_shortlink_wp_head'))
			remove_action('wp_head','wp_shortlink_wp_head',10,0);
		// Remove WP version
		remove_action('wp_head','wp_generator');
	}

	/**
		Register scripts
			@public
	**/
	static function register_scripts() {
		$scripts = self::get_config('SCRIPTS');
		if($scripts) {
			foreach($scripts as $key=>$params) {
				// Defaults
				$defaults = array(
					'deps'		=> FALSE,
					'ver'		=> '1.0',
					'footer'	=> FALSE
				);
				extract(wp_parse_args($params,$defaults));
				// Register script
				wp_register_script($key,$src,$deps,$ver,$footer);
			}
		}
	}

	/**
		Custom login page
			@private
	**/
	private static function login() {
		if(self::$vars['PAGENOW']=='wp-login.php') {
			if(self::get_option('login_custom')) {
				require(FEATHER_PATH.'modules/feather-login.php');
				FeatherLogin::init();
			}
		}
	}

	/**
		Maintenance page
			@private
	**/
	private static function maintenance() {
		$maintenance = self::get_option('maintenance');
		if($maintenance && !current_user_can('administrator')) {
			// Exclude login,register pages
			$exclude=array('wp-login.php','wp-register.php');
			if(!in_array(self::$vars['PAGENOW'],$exclude)) {
				$uri = esc_attr($_SERVER['REQUEST_URI']);
				// Get URL based on permalinks
				if(self::$vars['PERMALINKS']) {
					$url=substr($uri,-12);
					$redirect_url=home_url().'/_maintenance';
				} else {
					$url=isset($_REQUEST['p'])?esc_attr($_REQUEST['p']):'';
					$redirect_url=home_url().'/?p=_maintenance';
				}
				$in_admin = strstr($uri,'wp-admin');
				// Redirect if not maintenance URL
				if(('_maintenance'!=$url) && !$in_admin) {
					header('Location: '.$redirect_url,TRUE,307);
					exit;
				}
				// Load maintenance template
				if(!$in_admin) {
					require(FEATHER_PATH.'tmpl/feather-maintenance.php');
					exit(1);	
				}
			}
		}
	}

}

// Boot framework
FeatherCore::boot();
