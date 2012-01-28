<?php

$setting = array();

/* General : Theme Support
/*---------------------------------------------------------------------------*/

$setting['sections']['theme-support'] = array(
	'title'		=> 'Theme Support',
	'tab'		=> 'general'
);

//! Automatic Feed Links
$setting[] = array(
	'id'		=> 'auto_feed_links',
	'label'		=> 'Automatic Feed Links',
	'section'	=> 'theme-support',
	'type'		=> 'checkbox',
	'choices'	=> array(
		'auto_feed_links' => 'Enable support for feed links'
	)
);

//! Post Thumbnails
$setting[] = array(
	'id'		=> 'post_thumbnails',
	'label'		=> 'Post Thumbnails',
	'section'	=> 'theme-support',
	'type'		=> 'checkbox',
	'choices'	=> array(
		'post_thumbnails' => 'Enable support for post thumbnails'
	)
);

//! Post Formats
$setting[] = array(
	'id'		=> 'post_formats',
	'label'		=> 'Post Formats',
	'section'	=> 'theme-support',
	'type'		=> 'checkbox',
	'choices'	=> array(
		'post_formats' => 'Enable support for post formats'
	)
);

/* General : Post Formats
/*---------------------------------------------------------------------------*/

$setting['sections']['post-formats'] = array(
	'title'		=> 'Post Formats',
	'tab'		=> 'general'
);

//! Post Formats
$setting[] = array(
	'id'		=> 'post_formats',
	'label'		=> 'Enable Post Formats',
	'section'	=> 'post-formats',
	'type'		=> 'checkbox',
	'choices'	=> array(
		'post_format_aside'		=> 'Aside',
		'post_format_audio'		=> 'Audio',
		'post_format_chat'		=> 'Chat',
		'post_format_gallery'	=> 'Gallery',
		'post_format_image'		=> 'Image',
		'post_format_link'		=> 'Link',
		'post_format_quote'		=> 'Quote',
		'post_format_status'	=> 'Status',
		'post_format_video'		=> 'Video'
	)
);

/* General : Maintenance
/*---------------------------------------------------------------------------*/

$setting['sections']['maintenance'] = array(
	'title'		=> 'Maintenance',
	'tab'		=> 'general'
);

//! Enable
$setting[] = array(
	'id'		=> 'maintenance',
	'label'		=> 'Enable',
	'section'	=> 'maintenance',
	'type'		=> 'checkbox',
	'choices'	=> array(
		'maintenance' => 'Enable Maintenance Mode'
	)
);

/* Sidebar : Widgets
/*---------------------------------------------------------------------------*/

$setting['sections']['wordpress-widgets'] = array(
	'title'		=> 'Default Widgets',
	'tab'		=> 'sidebar'
);

//! Disable widgets
$setting[] = array(
	'id'		=> 'disable_wp_widgets',
	'label'		=> 'Disable WP Widgets',
	'section'	=> 'wordpress-widgets',
	'type'		=> 'checkbox',
	'choices'	=> array(
		'widget_wp_archives'		=> 'Archives',
		'widget_wp_calendar'		=> 'Calendar',
		'widget_wp_categories'		=> 'Categories',
		'widget_wp_custom_menu'		=> 'Custom Menu',
		'widget_wp_links'			=> 'Links',
		'widget_wp_meta'			=> 'Meta',
		'widget_wp_pages'			=> 'Pages',
		'widget_wp_recent_comments'	=> 'Recent Comments',
		'widget_wp_recent_posts'	=> 'Recent Posts',
		'widget_wp_rss'				=> 'RSS',
		'widget_wp_search'			=> 'Search',
		'widget_wp_tag_cloud'		=> 'Tag Cloud',
		'widget_wp_text'			=> 'Text'
	)
);

/* Login : Enable
/*---------------------------------------------------------------------------*/

$setting['sections']['login-enable'] = array(
	'title'		=> 'Login Page',
	'tab'		=> 'login'
);

//! Enable
$setting[]=array(
	'id'		=> 'login_custom',
	'label'		=> 'Enable',
	'section'	=> 'login-enable',
	'type'		=> 'checkbox',
	'choices'	=> array(
		'login_custom'	=> 'Enable custom login page'
	)
);

/* Login : Logo
/*---------------------------------------------------------------------------*/

$setting['sections']['login-logo'] = array(
	'title'		=> 'Logo',
	'tab'		=> 'login'
);

//! Logo
$setting[]=array(
	'id'		=> 'login_logo',
	'label'		=> 'Logo Image',
	'section'	=> 'login-logo',
	'type'		=> 'image'
);

//! Logo URL
$setting[]=array(
	'id'		=> 'login_logo_url',
	'label'		=> 'Logo URL',
	'section'	=> 'login-logo',
	'type'		=> 'text',
	'class'		=> 'regular-text'
);

/* Login : Colors
/*---------------------------------------------------------------------------*/

$setting['sections']['login-colors'] = array(
	'title'		=> 'Colors',
	'tab'		=> 'login'
);

//! Background Color
$setting[] = array(
	'id'		=> 'login_bg_color',
	'label'		=> 'Background Color',
	'section'	=> 'login-colors',
	'type'		=> 'colorpicker'
);

//! Link Color
$setting[] = array(
	'id'		=> 'login_link_color',
	'label'		=> 'Link Color',
	'section'	=> 'login-colors',
	'type'		=> 'colorpicker'
);

//! Link Color Hover
$setting[] = array(
	'id'		=> 'login_link_color_hover',
	'label'		=> 'Link Color Hover',
	'section'	=> 'login-colors',
	'type'		=> 'colorpicker'
);

/* Login : CSS
/*---------------------------------------------------------------------------*/

$setting['sections']['login-css'] = array(
	'title'		=> 'CSS',
	'tab'		=> 'login'
);

//! Custom CSS
$setting[] = array(
	'id'		=> 'login_css',
	'label'		=> 'Custom CSS',
	'section'	=> 'login-css',
	'type'		=> 'textarea',
	'rows'		=> '24'
);

/* Advanced : Cleanup <head>
/*---------------------------------------------------------------------------*/

$setting['sections']['cleanup-head'] = array(
	'title'		=> 'Cleanup &lt;head&gt;',
	'tab'		=> 'advanced'
);

//! Remove link elements
$setting[] = array(
	'id'		=> 'cleanup_head',
	'label'		=> 'Remove Link Elements',
	'section'	=> 'cleanup-head',
	'type'		=> 'checkbox',
	'choices'	=> array(
		'l10n.js'						  => 'l10n.js',
		'feed_links_extra'				  => 'feed_links_extra',
		'rsd_link'						  => 'rsd_link',
		'wlwmanifest_link'				  => 'wlwmanifest_link',
		'index_rel_link'				  => 'index_rel_link',
		'parent_post_rel_link'			  => 'parent_post_rel_link',
		'start_post_rel_link'			  => 'start_post_rel_link',
		'adjacent_posts_rel_link_wp_head' => 'adjacent_posts_rel_link_wp_head',
		'wp_shortlink_wp_head'			  => 'wp_shortlink_wp_head'
	)
);

/* Advanced : Comment Reply Javascript
/*---------------------------------------------------------------------------*/

$setting['sections']['comment-reply-js'] = array(
	'title'		=> 'Comment Reply Javascript',
	'tab'		=> 'advanced'
);

//! Disable comment-reply.js
$setting[] = array(
	'id'		=> 'commentreplyjs',
	'label'		=> 'Disable',
	'section'	=> 'comment-reply-js',
	'type'		=> 'checkbox',
	'choices'	=> array(
		'commentreply_js' => 'Disable comment-reply.js'
	)
);
