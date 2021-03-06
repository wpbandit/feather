<?php

/**
	Manages framework settings via Settings API

	The contents of this file are subject to the terms of the GNU General
	Public License Version 2.0. You may not use this file except in
	compliance with the license. Any of the license terms and conditions
	can be waived if you get permission from the copyright holder.

	Copyright (c) 2011 Bandit Media
	Jermaine Maree

		@package FeatherSettings
		@version 1.2
**/

//! FeatherSettings
class FeatherSettings extends FeatherBase {

	protected static
		//! Option name
		$option_name,
		//! Options
		$option,
		//! Sections,
		$sections,
		//! Settings
		$settings;

	/**
		Init
	**/
	static function init($option_name,$settings) {
		// Option Name
		self::$option_name = $option_name;
		// Options
		self::$option = get_option(self::$option_name);
		// Sections
		self::$sections = $settings['sections'];
		unset($settings['sections']);
		// Settings
		self::$settings = $settings;
		// Load form library
		require(FEATHER_PATH.'lib/feather-form.php');
		// Add Sections
		self::add_sections();
		// Add Fields
		self::add_fields();
	}

	/**
		Get option
	**/
	static function get_option($key) {
		$value = isset(self::$option[$key])?self::$option[$key]:FALSE;
		return $value;
	}

	/**
		Add sections
	**/
	private static function add_sections($prefix='feather-') {
		// Defaults
		$defaults = array(
			'title'		=> __('Default Title','feather'),
			'callback'	=> __CLASS__.'::print_section_heading'
		);
		// Loop through sections
		foreach(self::$sections as $id => $args) {
			extract(wp_parse_args($args,$defaults));
			// Prefix tab
			$tab = $prefix.$tab;
			// Add section
			add_settings_section($id,$title,$callback,$tab);
		}
	}

	/**
		Print section heading
	**/
	static function print_section_heading($args) {
		$id = $args['id'];
		$desc = '';
		if(isset(self::$sections[$id]['desc'])) {
			$desc = self::$sections[$id]['desc'];
			echo '<p>'.$desc.'</p>';
		}
	}

	/**
		Fields
	**/
	private static function add_fields($prefix='feather-') {
		// Defaults
		$defaults = array(
			'std'		=> '',
			'class'		=> '',
			'choices'	=> array(),
			'callback'	=> __CLASS__.'::print_field'
		);
		// Loop through fields
		foreach(self::$settings as $field) {
			extract(wp_parse_args($field,$defaults));
			// Set arguments
			$args = array(
				'id'		=> $id,
				'name'		=> self::$option_name.'['.$id.']',
				'std'		=> $std,
				'type'		=> $type,
				'class'		=> $class,
				'choices'	=> $choices
			);
			// Set tab
			$tab = $prefix.self::$sections[$section]['tab'];
			// Add field
			add_settings_field($id,$label,$callback,$tab,$section,$args);
		}
	}

	/**
		Print field
	**/
	static function print_field($args) {
		// Get field type
		$type = $args['type'];
		unset($args['type']);
		// Create field
		switch ($type) {
			// Checkbox
			case 'checkbox':
				$output = self::field_checkbox($args);
				break;
			// Color
			case 'colorpicker':
				$output = self::field_colorpicker($args);
				break;
			// Image
			case 'image':
				$output = self::field_image($args);
				break;
			case 'radio':
				$output = self::field_radio($args);
				break;
			// Select
			case 'select':
				$output = self::field_select($args);
				break;
			// Text
			case 'text':
				$output = self::field_text($args);
				break;
			// Textarea
			case 'textarea':
				$output = self::field_textarea($args);
				break;
		}
		// Print field
		echo $output;
	}

	/**
		Checkbox field
	**/
	private static function field_checkbox($args) {
		extract($args);
		$field = '';
		foreach($choices as $key=>$label) {
			// Get value
			$value = self::get_option($key)?self::get_option($key):$std;
			// Set attributes
			$attrs = array(
				'id'	=> 'feather_'.$key,
				'name'	=> self::$option_name.'['.$key.']',
				'class'	=> $class
			);
			// Create checkbox field
			$field .= FeatherForm::checkbox($attrs,$value);
			$field .= ' <label for="'.$attrs['name'].'">'.$label.'</label><br />';
		}
		// Return field
		return $field;
	}

	/**
		Colorpicker field
	**/
	private static function field_colorpicker($args) {
		extract($args);
		// Get value
		$value = self::get_option($id)?self::get_option($id):$std;
		// Colorpicker color
		$bg = $value?$value:'ccc';
		// Colorpicker div
		$field = '<div class="feather-colorpicker"><div style="background-color:#'.$bg.';"></div></div>';
		// Set attributes
		$attrs = array(
			'id'		=> 'feather_'.$id,
			'name'		=> $name,
			'class'		=> 'small-text',
			'maxlength'	=> '6'
		);
		// Create text field
		$field .= FeatherForm::text($attrs,$value);
		// Return field
		return $field;
	}

	/**
		Image field
	**/
	private static function field_image($args) {
		extract($args);
		// Get value
		$value = self::get_option($id)?self::get_option($id):$std;
		// Set text attributes
		$attrs = array(
			'id'	=> 'feather_'.$id,
			'name'	=> $name,
			'class'	=> $class?$class:'regular-text'
		);
		// Create text field
		$field = FeatherForm::text($attrs,$value);
		// Set button attributes
		$attrs2 = array(
			'id'	=> $id.'_button',
			'class'	=> 'button-secondary feather-image-button',
			'value' => __('Select Image','feather')
		);
		// Create button
		$field .= FeatherForm::button($attrs2);
		$field .= '<div class="feather-image-placeholder"></div>';
		// Return field
		return $field;
	}

	/**
		Radio field
	**/
	private static function field_radio($args) {
		extract($args);
		$field = '';
		// Get selected value
		$selected = self::get_option($id)?self::get_option($id):$std;
		// Set default
		if(!$selected)
			$selected = key($choices);
		foreach($choices as $key=>$label) {
			// Set attributes
			$attrs = array(
				'name'	=> self::$option_name.'['.$id.']',
				'class'	=> $class
			);
			// Create radio field
			$field .= FeatherForm::radio($attrs,$key,$selected);
			$field .= ' <label>'.$label.'</label><br />';
		}
		// Return field
		return $field;
	}

	/**
		Select field
	**/
	private static function field_select($args) {
		extract($args);
		// Get value
		$value = self::get_option($id)?self::get_option($id):$std;
		// Set attributes
		$attrs = array(
			'id'	=> 'feather_'.$id,
			'name'	=> $name,
			'class'	=> $class
		);
		// Create select field
		$field = FeatherForm::select($attrs,$value,$choices);
		// Return field
		return $field;
	}

	/**
		Text field
	**/
	private static function field_text($args) {
		extract($args);
		// Get value
		$value = self::get_option($id)?self::get_option($id):$std;
		// Set attributes
		$attrs = array(
			'id'	=> 'feather_'.$id,
			'name'	=> $name,
			'class'	=> $class?$class:'regular-text'
		);
		// Create text field
		$field = FeatherForm::text($attrs,$value);
		// Return field
		return $field;
	}

	/**
		Textarea field
	**/
	private static function field_textarea($args) {
		extract($args);
		// Get value
		$value = self::get_option($id)?self::get_option($id):$std;
		// Set attributes
		$attrs = array(
			'id'	=> 'feather_'.$id,
			'name'	=> $name,
			'class'	=> $class?$class:'large-text',
			'cols'	=> isset($cols)?$cols:'50',
			'rows'	=> isset($rows)?$rows:'8'
		);
		// Create textarea field
		$field = '<p>'.FeatherForm::textarea($attrs,$value).'</p>';
		// Return field
		return $field;
	}

}
