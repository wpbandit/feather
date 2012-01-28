<?php

/**
	Custom Meta Builder for the Bandit Framework

	The contents of this file are subject to the terms of the GNU General
	Public License Version 2.0. You may not use this file except in
	compliance with the license. Any of the license terms and conditions
	can be waived if you get permission from the copyright holder.

	Copyright (c) 2011 Bandit Media
	Jermaine Marée

		@package FeatherMeta
		@version 1.1
**/

//! Add custom meta boxes
class FeatherMeta extends FeatherBase {

	protected static
		//! Nonce
		$nonce;

	/**
		Init meta
			@public
	**/
	static function init() {
		global $current_screen;
		// Loop through meta config
		foreach(self::$theme_meta as $mid=>$meta) {
			// Does meta apply to current page?
			if($meta['page']==$current_screen->post_type) {
				if(!isset($meta['count'])) {
					// Add meta box
					self::add_meta_box($mid,$meta);
				} else {
					for($i=1;$i<=$meta['count']; $i++) {
						// Temp array for fields
						$tmp=array();
						// Store meta in tmp array
						$tmp['meta']=$meta;
						// Append number to fields
						$tmp['mid']=$mid.$i;
						foreach($meta['args'] as $fid=>$field) {
							$tmp['meta']['args'][$fid]['id']=$meta['args'][$fid]['id'].$i;
							$tmp['meta']['args'][$fid]['label']=$i.'. '.$meta['args'][$fid]['label'];
						}
						// Add meta box
						self::add_meta_box($tmp['mid'],$tmp['meta']);
					}
					// Unset $tmp
					unset($tmp);
				}
			}
		}
	}

	/**
		Process meta field
			@private
	**/
	private static function add_meta_box($mid,array $fields) {
		// Extract fields
		extract($fields);

		// Callback
		if(!isset($callback))
			$callback=__CLASS__.'::create_meta_box';
		// Context
		if(!isset($context)) { $context='advanced'; }
		// Priority
		if(!isset($priority)) { $priority='low'; }
		// Args
		if(!isset($args)) { $args=array(); }

		// Non-template meta box
		if(!isset($template))
			add_meta_box($mid,$title,$callback,$page,$context,$priority,$args);

		// Template specific meta box
		if(isset($template)) {
			// Get post id
			if(isset($_GET['post'])) { $post_id=esc_attr($_GET['post']); }
			if(isset($_POST['post_ID'])) { $post_id=esc_attr($_POST['post_ID']); }
			// Get template
			if(isset($post_id))
				$page_template=get_post_meta($post_id,'_wp_page_template',TRUE);
			// Create meta box, if template matches
			if(isset($page_template) && in_array($page_template,$template))
				add_meta_box($mid,$title,$callback,$page,$context,$priority,$args);
		}
	}

	/**
		Create meta boxes
			@public
	**/
	static function create_meta_box($post,$metabox) {
		// Meta nonce
		if(!isset(self::$nonce)) {
			// Create nonce field
			wp_nonce_field( plugin_basename( __FILE__ ),'feather_meta_nonce');
			// Prevent duplicate nonce fields
			self::$nonce=TRUE;
		}
		// Begin table
		$output='<table class="form-table">'."\n";
		// Loop through metabox fields
		foreach ($metabox['args'] as $field) {
			// Disable editor
			if(isset($field['disable_editor']))
				echo '<style>#postdivrich{display:none;}</style>';
			// Determine field count
			$count=isset($field['count'])?$field['count']:'1';
			// Single field
			if($count=='1') {
				// Process field
				$field=self::process_field($field,$post->ID);
				// Create field
				$output.=self::create_field($field);
			}
			// Multiple fields
			if($count > '1') {
				for($i=1;$i<=$count;$i++) {
					// Multiple field array
					$mfield=array();
					// Process field
					$mfield[$i]=self::process_field($field,$post->ID,$i);
					// Create field
					$output.=self::create_field($mfield[$i]);
				}
				// Unset multiple field array
				unset($mfield);
			}
		}
		// End table
		$output.='</table>'."\n";
		// Display field
		echo $output;
	}

	/**
		Process field
			@private
	**/
	private static function process_field(array $field,$post_id,$count=NULL) {
		if(!isset($count)) {
			// Set field id
			$field['id']=$field['id'];
		} else {
			// Set field id
			$field['id']=$field['id'].$count;
			// Set field label
			$field['label']=$field['label'].' '.$count;
		}
		// Set id,name attributes
		$field['attrs']=array(
			'id'=>$field['id'],
			'name'=>$field['id']
		);
		// Set class attribute, if exists
		if(isset($field['class'])) { $field['attrs']['class']=$field['class']; }
		// Get current field value
		$field['value']=get_post_meta($post_id,$field['id'],TRUE);
		// Set standard value, if no value set
		if(isset($field['std']))
			$field['value']=$field['value']?$field['value']:$field['std'];
		// Return field
		return $field;
	}

	/**
		Create field
			@private
	**/
	private static function create_field(array $field) {
		// Extract args
		extract($field);
		// Data validation
		$value=esc_attr($value);
		// Begin table row
		$output='<tr>'."\n";
		$output.='<th><label for="'.$id.'">'.$label.'</label></th>'."\n";
		$output.='<td>'."\n";
		// Checkbox
		if('checkbox'==$type) {
			$output.=FeatherForm::checkbox($attrs,$value);
		}
		// Select
		if('select'==$type) {
			$output.=FeatherForm::select($attrs,$value,$options);
		}
		// Text
		if('text'==$type) {
			if(!isset($attrs['class']))
				$attrs['class']='large-text';
			$output.=FeatherForm::text($attrs,$value);
		}
		// Textarea
		if('textarea'==$type) {
			$attrs['cols']=isset($cols)?$cols:'60';
			$attrs['rows']=isset($rows)?$rows:'8';
			$attrs['class']=isset($class)?$class:'large-text';
			$output.=FeatherForm::textarea($attrs,$value);
		}
		// End table row
		$output.='</td>'."\n";
		$output.='</tr>'."\n";
		// Return field
		return $output;
	}

	/**
		Save meta
			@public
	**/
	static function save_meta($post_id) {
		// If autosave routine, do not save
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
			return;
		// Set nonce
		$nonce=isset($_POST['feather_meta_nonce'])?$_POST['feather_meta_nonce']:'';
		// Verify nonce
		if(!wp_verify_nonce($nonce,plugin_basename( __FILE__ ))) { return; }

		// Verify permission to save meta
		if ('page'==$_POST['post_type'])
			if(!current_user_can('edit_page',$post_id)) { return; }
		if ('post'==$_POST['post_type'])
			if(!current_user_can('edit_post',$post_id)) { return; }

		// Extend $allowedposttags
		self::extend_allowedposttags();

		// Loop through fields
		foreach(self::$theme_meta as $mid=>$meta) {
			if($meta['page']==$_POST['post_type']) {
				// Single
				if(isset($meta['args']) && !isset($meta['count'])) {
					foreach ($meta['args'] as $field) {
						// Determine field count
						$count=isset($field['count'])?$field['count']:'1';
						// Save field
						for($i=1;$i<=$count;$i++) {
							// Prefix field ID
							if($count=='1')
								$id=$field['id'];
							else
								$id=$field['id'].$i;
							// Old Value
							$old=get_post_meta($post_id,$id,TRUE);
							// New value
							$new=isset($_POST[$id])?$_POST[$id]:FALSE;
							// Save data
							if($new && $new!=$old)
								update_post_meta($post_id,$id,wp_filter_post_kses($new));
							if(''==$new && $old)
								delete_post_meta($post_id,$id,$old);
						}
					}
				}
				// Multiple
				if(isset($meta['args']) && isset($meta['count'])) {
					for($i=1;$i<=$meta['count'];$i++) {
						foreach ($meta['args'] as $field) {
							// Append number to field id
							$id=$field['id'].$i;
							// Old Value
							$old=get_post_meta($post_id,$id,TRUE);
							// New value
							$new=isset($_POST[$id])?$_POST[$id]:FALSE;
							// Save data
							if($new && $new!=$old)
								update_post_meta($post_id,$id,wp_filter_post_kses($new));
							if(''==$new && $old)
								delete_post_meta($post_id,$id,$old);
						}
					}
				}
			}
		}
	}

	/**
		Extend $allowedposttags
			@private
	**/
	private static function extend_allowedposttags() {
		global $allowedposttags;
		// iframe
		$allowedposttags["iframe"] = array(
			"id" => array(),
			"class" => array(),
			"title" => array(),
			"style" => array(),
			"align" => array(),
			"frameborder" => array(),
			"longdesc" => array(),
			"marginheight" => array(),
			"marginwidth" => array(),
			"name" => array(),
			"scrolling" => array(),
			"src" => array(),
			"height" => array(),
			"width" => array(),
			"allowfullscreen" => array()
		);
		// object
		$allowedposttags["object"] = array(
			"height" => array(),
			"width" => array()
		);
		// param
		$allowedposttags["param"] = array(
			"name" => array(),
			"value" => array()
		);
		// embed
		$allowedposttags["embed"] = array(
			"src" => array(),
			"type" => array(),
			"allowfullscreen" => array(),
			"allowscriptaccess" => array(),
			"height" => array(),
			"width" => array()
		);
	}

}
