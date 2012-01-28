<?php

/**
	Manages framework settings via Settings API

	The contents of this file are subject to the terms of the GNU General
	Public License Version 2.0. You may not use this file except in
	compliance with the license. Any of the license terms and conditions
	can be waived if you get permission from the copyright holder.

	Copyright (c) 2011 Bandit Media
	Jermaine Marée

		@package FeatherSettings
		@version 1.1
**/

//! Settings Class
class FeatherSettings extends FeatherBase {

	protected static
		//! Temp Variable
		$tmp;

	/**
		Add section
			@param $section array
			@public
	**/
	static function add_section(array $section,$option) {
		extract($section);
		add_settings_section($id,$title,__CLASS__.'::section_callback',$option.'-'.$tab);
	}

	/**
		Section callback
			@public
	**/
	static function section_callback($section) {
		// Check for section id
		if($section['id']) {
			$id=substr($section['id'],8);
			// Reference tmp variable to settings
			if(!isset(self::$tmp['settings'])) {
				// Framework settings
				if(isset(self::$setting))
					self::$tmp['settings']=&self::$setting;
				// Theme settings
				if(isset(self::$theme_setting))
					self::$tmp['settings']=&self::$theme_setting;
			}
			// Print section desc
			if(self::$tmp['settings'][$id]['desc'])
				self::print_section_desc(self::$tmp['settings'][$id]['desc']);
		}
	}

	/**
		Add field
			@param $field array
			@public
	**/
	static function add_field(array $field,$option) {
		extract($field);
		// Setting field callback
		$callback=isset($callback)?$callback:__CLASS__.'::print_field';
		// Add settings field
		add_settings_field($id,$label,$callback,$option.'-'.$tab,$section,$field);
	}

	/**
		Print section description
			@param $function string
			@public
	**/
	private static function print_section_desc($desc) {
		echo '<p>'.$desc.'</p>';
	}

	/**
		Print field
			@param $field array
			@public
	**/
	static function print_field($args) {
		extract($args);
		// Verify setting has been set
		if(!isset($setting)) { return; }
		// Set option function
		if(!isset($optionfunc)) {
			if('feather'==$setting)
				$optionfunc='FeatherCore::get_option';
			else
				$optionfunc='FeatherCore::get_theme_option';
		}
		// Set $output to empty string
		$output='';
		// Set attributes
		$attrs=array(
			'id'=>$setting.'['.$id.']',
			'name'=>$setting.'['.$id.']'
		);
		// Set class
		if(isset($class)) { $attrs['class']=$class; }

		// Checkbox
		if('checkbox'==$type) {
			foreach($choices as $key=>$label) {
				// Set attributes
				$attrs=array(
					'id'=>$setting.'['.$key.']',
					'name'=>$setting.'['.$key.']'
				);
				if($setting=='feather' && isset(self::$config['OPTION_REQUIRED'][$key])) {
					// Set value
					$value=self::$config['OPTION_REQUIRED'][$key];
					// Disable setting
					$attrs['disabled']='disabled';
					// Lighten label
					$label='<span style="color:#777;">'.$label.'</span>';
				} else {
					// Get value
					$value=call_user_func_array($optionfunc,array($key));
				}
				// Create checkbox setting
				$output.='<label for="'.$attrs['name'].'">';
				$output.=FeatherForm::checkbox($attrs,$value);
				$output.=' '.$label.'</label><br />';
			}
		}

		// Colorpicker
		if('colorpicker'==$type) {
			// Get value
			$value=call_user_func_array($optionfunc,array($id));
			$bg=$value?$value:'ccc';
			// Text Field
			$output='<div class="feather-colorpicker"><div style="background-color: #'.$bg.';"></div></div>';
			$output.='<input id="'.$attrs['name'].'" type="text" name="'.$attrs['name'].'" '.
				'class="small-text" value="'.esc_attr($value).'" maxlength="6" />';
		}

		// Image
		if('image'==$type) {
			// Get value
			$value=call_user_func_array($optionfunc,array($id));
			// Text field
			$output='<input id="'.$attrs['name'].'" type="text" name="'.$attrs['name'].'" '.
				'class="regular-text" value="'.esc_attr($value).'" />';
			// Button
			$output.='<input id="'.$setting.'['.$id.'_button]" '.
				'class="button-secondary feather-image-button" type="button" '.
				'value="Select Image" data-id="'.$id.'" />';
			// Image placeholder
			$output.='<div id="feather_'.$id.'_placeholder" class="feather_image_placeholder">';
			if($value) {
				$output.='<img src="'.$value.'" />';
			}
			$output.='</div>';
		}

		// Radio
		if('radio'==$type) {
			// Get value
			$val=call_user_func_array($optionfunc,array($id));
			$selected=$val?$val:key($choices);
			foreach($choices as $value=>$label) {
				// Set attributes
				$attrs=array(
					'id'=>$setting.'['.$id.']',
					'name'=>$setting.'['.$id.']'
				);
				// Create radio setting
				$output.='<label>'.FeatherForm::radio($attrs,$value,$selected).'<span>'.$label.'</span></label><br />';
			}
		}

		// Select
		if('select'==$type) {
			// Get value
			$value=call_user_func_array($optionfunc,array($id));
			// Create select field
			$output.=FeatherForm::select($attrs,$value,$choices);
		}

		// Text
		if('text'==$type) {
			// Get value
			$value=call_user_func_array($optionfunc,array($id));
			// Set class
			if(!isset($attrs['class']))
				$attrs['class']='regular-text';
			// Create text setting
			$output.=FeatherForm::text($attrs,$value);
		}

		// Textarea
		if('textarea'==$type) {
			// Get value
			$value=call_user_func_array($optionfunc,array($id));
			// Set attributes
			$attrs['cols']=isset($cols)?$cols:'50';
			$attrs['rows']=isset($rows)?$rows:'8';
			$attrs['class']=isset($class)?$class:'large-text';
			// Create textarea setting
			$output.='<p>'.FeatherForm::textarea($attrs,$value).'</p>';
		}

		// Print field
		echo $output;
	}

}
