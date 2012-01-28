<?php

/**
	Feather Login Module

	The contents of this file are subject to the terms of the GNU General
	Public License Version 2.0. You may not use this file except in
	compliance with the license. Any of the license terms and conditions
	can be waived if you get permission from the copyright holder.

	Copyright (c) 2011 Bandit Media
	Jermaine Maree

		@package FeatherLogin
		@version 1.2.1
**/


//! Login
class FeatherLogin extends FeatherBase {

	/**
		Init
			@public
	**/
	static function init() {
		// Login Head
		if(version_compare(self::$vars['WP_VERSION'],'3.3') < 0) {
			add_action('login_head',__CLASS__.'::login_head_32');
		} else {
			add_action('login_head',__CLASS__.'::login_head');
		}
		// Logo URL
		if(self::get_option('login_logo_url'))
			add_filter('login_headerurl',__CLASS__.'::login_headerurl');
	}

	/**
		Login head - WP 3.2 and below
			@public
	**/
	static function login_head_32() {
		// Get options
		$logo=self::get_option('login_logo');
		$bg_color=self::get_option('login_bg_color');
		$link_color=self::get_option('login_link_color');
		$link_color_hover=self::get_option('login_link_color_hover');
		$login_css=self::get_option('login_css');
		// Build CSS
		$output='<style type="text/css">';
		// Background
		if($bg_color) {
			$output.='html { background-color: #'.$bg_color.'!important; }';
			$output.='#nav, #backtoblog { text-shadow: none }';
		}
		// Logo
		if($logo) {
			// Get image size
			$size = getimagesize($logo);
			// Set width and height
			$width = $size?'width:'.$size[0].'px;':'';
			$height = $size?'height:'.$size[1].'px':'';
			// Logo CSS
			$output.=' h1 a { background:url('.$logo.') no-repeat; '.$width.$height.' }';
		}
		// Link Color
		if($link_color) {
			$output.='.login #nav a, .login #backtoblog a { color:#'.$link_color.'!important; }';
		}
		// Link Color Hover
		if($link_color_hover) {
			$output.='.login #nav a:hover, .login #backtoblog a:hover { color:#'.$link_color_hover.'!important; }';
		}
		// Custom CSS
		if($login_css) {
			$output.=$login_css;
		}
		$output.='</style>';
		// Print CSS
		echo $output;
	}

		/**
		Login head
			@public
	**/
	static function login_head() {
		// Get options
		$logo=self::get_option('login_logo');
		$bg_color=self::get_option('login_bg_color');
		$link_color=self::get_option('login_link_color');
		$link_color_hover=self::get_option('login_link_color_hover');
		$login_css=self::get_option('login_css');
		// Build CSS
		$output='<style type="text/css">';
		// Background
		if($bg_color) {
			$output.='html, body.login { background-color: #'.$bg_color.'!important; }';
			$output.='.login #nav, .login #backtoblog { text-shadow: none }';
		}
		// Logo
		if($logo) {
			// Get image size
			$size = getimagesize($logo);
			// Set width and height
			$width = $size?'width:'.$size[0].'px;':'';
			$height = $size?'height:'.$size[1].'px':'';
			// Logo CSS
			$output.='.login h1 a { background:url('.$logo.') no-repeat; '.$width.$height.' }';
		}
		// Link Color
		if($link_color) {
			$output.='.login #nav a, .login #backtoblog a { color:#'.$link_color.'!important; }';
		}
		// Link Color Hover
		if($link_color_hover) {
			$output.='.login #nav a:hover, .login #backtoblog a:hover { color:#'.$link_color_hover.'!important; }';
		}
		// Custom CSS
		if($login_css) {
			$output.=$login_css;
		}
		$output.='</style>';
		// Print CSS
		echo $output;
	}

	/**
		Login Logo URL
			@return string
			@param $url string
			@public
	**/
	static function login_headerurl($url) {
		return self::get_option('login_logo_url');
	}

}
