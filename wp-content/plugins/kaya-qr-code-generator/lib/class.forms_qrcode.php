<?php
/** 
 * Kaya QR Code Generator - Forms_QRCode Class
 * Set Forms QRCode
 */

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly
}

if (!class_exists('WPKQCG_Forms_QRCode'))
{
	class WPKQCG_Forms_QRCode
	{
		/**
		* return Array of fields strucutre
		*/
		private static function get_fields()
		{
			//	Fields setting
			$fields = array(
				array(
					'_meta_type' => 'text', 
					'_meta_slug' => 'title', 
					'_label' => __('Title:', WPKQCG_TEXT_DOMAIN), 
					'_description' => __('Displayed as widget title or as heading 2 for shortcodes.', WPKQCG_TEXT_DOMAIN), 
					'_class' => 'widefat',
					'_value_default' => ''
				),
				array(
					'_meta_type' => 'text', 
					'_meta_slug' => 'content', 
					'_label' => __('Content to encode in QR Code:', WPKQCG_TEXT_DOMAIN), 
					'_description' => __('Leave empty to use current page url, or enter content to be encoded in QR Code.', WPKQCG_TEXT_DOMAIN), 
					'_class' => 'widefat',
					'_value_default' => ''
				),
				array(
					'_meta_type' => 'checkbox', 
					'_meta_slug' => 'dynamic_content', 
					'_label' => __('Use dynamic content (other shortcodes)', WPKQCG_TEXT_DOMAIN),
					'_value_default' => 0
				),
				array(
					'_meta_type' => 'select', 
					'_meta_slug' => 'ecclevel', 
					'_label' => __('Information repetition level:', WPKQCG_TEXT_DOMAIN), 
					'_description' => __('Ability to correct read errors.', WPKQCG_TEXT_DOMAIN),
					'_options' => array(
									'L' => __('Low ~7%', WPKQCG_TEXT_DOMAIN),
									'M' => __('Medium ~15%', WPKQCG_TEXT_DOMAIN),
									'Q' => __('Quarter ~25%', WPKQCG_TEXT_DOMAIN),
									'H' => __('High ~30%', WPKQCG_TEXT_DOMAIN)
								),
					'_value_default' => 'L'
				),
				array(
					'_meta_type' => 'text', 
					'_meta_slug' => 'size', 
					'_label' => __('Size:', WPKQCG_TEXT_DOMAIN), 
					'_description' => __('Leave empty for automatic size, or enter size in px.', WPKQCG_TEXT_DOMAIN),
					'_value_default' => ''
				),
				array(
					'_meta_type' => 'text', 
					'_meta_slug' => 'color', 
					'_label' => __('Color:', WPKQCG_TEXT_DOMAIN), 
					'_description' => __('Leave empty for default color, or use Hex color code as "#000000".', WPKQCG_TEXT_DOMAIN),
					'_value_default' => ''
				),
				array(
					'_meta_type' => 'text', 
					'_meta_slug' => 'bgcolor', 
					'_label' => __('Background Color:', WPKQCG_TEXT_DOMAIN), 
					'_description' => __('Leave empty for default color, or use Hex color code as "#FFFFFF".', WPKQCG_TEXT_DOMAIN),
					'_value_default' => ''
				),
				array(
					'_meta_type' => 'select', 
					'_meta_slug' => 'align', 
					'_label' => __('Alignment:', WPKQCG_TEXT_DOMAIN), 
					'_description' => __('Horizontal alignment.', WPKQCG_TEXT_DOMAIN),
					'_options' => array(
									'alignnone' => __('None', WPKQCG_TEXT_DOMAIN),
									'alignleft' => __('Left', WPKQCG_TEXT_DOMAIN),
									'aligncenter' => __('Center', WPKQCG_TEXT_DOMAIN),
									'alignright' => __('Right', WPKQCG_TEXT_DOMAIN)
								),
					'_value_default' => 'alignnone'
				),
				array(
					'_meta_type' => 'checkbox', 
					'_meta_slug' => 'css_shadow', 
					'_label' => __('Add shadows to QR Code image', WPKQCG_TEXT_DOMAIN),
					'_value_default' => 0
				),
				array(
					'_meta_type' => 'text', 
					'_meta_slug' => 'url', 
					'_label' => __('Destination URL:', WPKQCG_TEXT_DOMAIN), 
					'_description' => __('QR Code image clickable link. URL with http://', WPKQCG_TEXT_DOMAIN),
					'_class' => 'widefat',
					'_value_default' => ''
				),
				array(
					'_meta_type' => 'checkbox', 
					'_meta_slug' => 'new_window', 
					'_label' => __('Open link in a new window', WPKQCG_TEXT_DOMAIN),
					'_value_default' => 0
				),
				array(
					'_meta_type' => 'text', 
					'_meta_slug' => 'alt', 
					'_label' => __('Image alternate text:', WPKQCG_TEXT_DOMAIN), 
					'_description' => __('Leave empty for default image alternate text "QR Code".', WPKQCG_TEXT_DOMAIN), 
					'_class' => 'widefat',
					'_value_default' => ''
				),
			);
			
			return $fields;
		}
		
		/**
		* return Array of fields default values
		*/
		public static function get_fields_default_value()
		{
			$fields_default = array();
			//	get form fields array
			$fields_Tab = WPKQCG_Forms_QRCode::get_fields();
			//	get default values
			foreach ($fields_Tab as $field)
			{
				$fields_default[$field['_meta_slug']] = $field['_value_default'];
			}
			
			return $fields_default;
		}
		
		/**
		* return Array field by slug
		*/
		private static function get_field_by_slug($p_slug)
		{
			//	get form fields array
			$fields_Tab = WPKQCG_Forms_QRCode::get_fields();
			//	get field by slug
			foreach ($fields_Tab as $field)
			{
				if ($field['_meta_slug']==$p_slug)
				{
					return $field;
				}
			}
			
			return false;
		}
		
		/**
		* return options Object
		*/
		private static function get_form_options()
		{
			$options_Obj = new stdClass();
			//	Options setting
			$options_Tab = array(
				'_form_onchange' => 'wpkqcg_qrcode_sc_gen',
			);
			//	switch from array to Object
			foreach ($options_Tab as $attr => $val)
			{
				$options_Obj->$attr = $val;
			}
			
			return $options_Obj;
		}
		
		/**
		* return Array accepted options
		*/
		private static function get_accepted_options($p_slug)
		{
			$field = WPKQCG_Forms_QRCode::get_field_by_slug($p_slug);
			
			if (isset($field['_options'])) return $field['_options'];
			
			return false;
		}
		
		/**
		* Display HTML form fields
		*/
		public static function display_form_fields($p_fields_val = false, $p_function_ids = false)
		{
			//	get form fields
			$form_fields = WPKQCG_Forms_QRCode::get_form_fields($p_fields_val, $p_function_ids);
			//	display form fields
			$o = WPKQCG_Forms::display_form($form_fields, false);
			
			return $o;
		}

		/**
		* Display HTML form fields with form options
		*/
		public static function display_form_fields_options($p_fields_val = false, $p_function_ids = false)
		{
			//	get form fields
			$form_fields = WPKQCG_Forms_QRCode::get_form_fields($p_fields_val, $p_function_ids);
			//	get form options
			$form_options = WPKQCG_Forms_QRCode::get_form_options();
			//	display form fields
			$o = WPKQCG_Forms::display_form($form_fields, $form_options);
			
			return $o;
		}
		
		/**
		* return Array with fields Object
		*/
		private static function get_form_fields($p_fields_val = false, $p_function_ids = false)
		{
			$fields_Obj = array();
			//	get form fields array
			$fields_Tab = WPKQCG_Forms_QRCode::get_fields();
			
			foreach ($fields_Tab as $field)
			{
				$form_field = new stdClass();
				//	switch from array to Object
				foreach ($field as $attr => $val)
				{
					$form_field->$attr = $val;
				}
				//	get field value
				if (empty($p_fields_val))
				{
					$field_val = false;
				}
				else
				{
					$field_val = isset($p_fields_val[$form_field->_meta_slug]) ? $p_fields_val[$form_field->_meta_slug] : false;
				}
				//	validate field value
				$form_field->_value = WPKQCG_Forms_QRCode::validate_field($form_field->_meta_slug, $field_val, $form_field->_value_default);
				//	get callable functions to set ids values
				if (!$p_function_ids)
				{
					$function_ids_id = false;
					$function_ids_name = false;
				}
				else
				{
					$function_ids_id = $p_function_ids['_id'];
					$function_ids_name = $p_function_ids['_name'];
				}
				//	set ids values : field id and name
				$form_field->_id = WPKQCG_Forms_QRCode::set_field_id($form_field->_meta_slug, $function_ids_id);
				$form_field->_name = WPKQCG_Forms_QRCode::set_field_name($form_field->_meta_slug, $function_ids_name);
				//	Add object to the list
				$fields_Obj[] = $form_field;
			}
			
			return $fields_Obj;
		}
		
		/**
		* Set one field id
		*/
		private static function set_field_id($p_meta_slug, $p_function_id = false)
		{
			$field_id = '';
			if (!empty($p_function_id) && method_exists($p_function_id[0], $p_function_id[1]))
			{
				//	get id through callable function
				$field_id = call_user_func(array($p_function_id[0], $p_function_id[1]), $p_meta_slug);
			}
			else
			{
				//	generate unique field id
				$field_unique_id = rand(0, 99) . uniqid() . rand(0, 99);
				$field_id = 'wpkqcg_data_' . $p_meta_slug . '_' . $field_unique_id;
			}
			
			return $field_id;
		}
		
		/**
		* Set one field name
		*/
		private static function set_field_name($p_meta_slug, $p_function_name = false)
		{
			$field_name = '';
			if (!empty($p_function_name) && method_exists($p_function_name[0], $p_function_name[1]))
			{
				//	get name through callable function
				$field_name = call_user_func(array($p_function_name[0], $p_function_name[1]), $p_meta_slug);
			}
			else
			{
				//	generate field name
				$field_name = 'wpkqcg[data][' . $p_meta_slug . ']';
			}
			
			return $field_name;
		}
		
		/**
		* validate all fields values
		*/
		public static function validate_fields($p_fields_values)
		{
			$fields_valid = array();
			//	get form fields array
			$fields_Tab = WPKQCG_Forms_QRCode::get_fields();
			
			foreach ($fields_Tab as $field)
			{
				$_meta_slug = $field['_meta_slug'];
				$_value_default = $field['_value_default'];
				//	validate field value
				$fields_valid[$_meta_slug] = WPKQCG_Forms_QRCode::validate_field($_meta_slug, (isset($p_fields_values[$_meta_slug]) ? $p_fields_values[$_meta_slug] : NULL), $_value_default);
			}
			
			return $fields_valid;
		}
		
		/**
		* validate one field value
		*/
		private static function validate_field($p_field_slug, $p_field_value, $p_field_value_default)
		{
			$field_valid = '';

			if ($p_field_slug=='title')
			{
				//	title is string
				$field_valid = (!empty($p_field_value) && is_string($p_field_value)) ? $p_field_value : $p_field_value_default;
				$field_valid = sanitize_text_field($field_valid);
			}
			elseif($p_field_slug=='content')
			{
				//	content is string
				$field_valid = (!empty($p_field_value) && is_string($p_field_value)) ? $p_field_value : $p_field_value_default;
				$field_valid = sanitize_text_field($field_valid);
			}
			elseif($p_field_slug=='dynamic_content')
			{
				//	dynamic_content is numeric
				$field_valid = (!empty($p_field_value) && is_numeric($p_field_value)) ? $p_field_value : $p_field_value_default;
				$field_valid = sanitize_text_field($field_valid);
			}
			elseif($p_field_slug=='ecclevel')
			{
				//	ecclevel is string and key
				$accepted_options = WPKQCG_Forms_QRCode::get_accepted_options($p_field_slug);
				$field_valid = (!empty($p_field_value) && is_string($p_field_value) && array_key_exists($p_field_value, $accepted_options)) ? $p_field_value : $p_field_value_default;
				$field_valid = sanitize_text_field($field_valid);
			}
			elseif($p_field_slug=='css_shadow')
			{
				//	css_shadow is numeric
				$field_valid = (!empty($p_field_value) && is_numeric($p_field_value)) ? $p_field_value : $p_field_value_default;
				$field_valid = sanitize_text_field($field_valid);
			}
			elseif($p_field_slug=='align')
			{
				//	align is string and key
				$accepted_options = WPKQCG_Forms_QRCode::get_accepted_options($p_field_slug);
				$field_valid = (!empty($p_field_value) && is_string($p_field_value) && array_key_exists($p_field_value, $accepted_options)) ? $p_field_value : $p_field_value_default;
				$field_valid = sanitize_text_field($field_valid);
			}
			elseif($p_field_slug=='size')
			{
				//	size is numeric
				$field_valid = (!empty($p_field_value) && is_numeric($p_field_value)) ? $p_field_value : $p_field_value_default;
				$field_valid = sanitize_key($field_valid);
			}
			elseif($p_field_slug=='color')
			{
				//	color is string
				$field_valid = (!empty($p_field_value) && is_string($p_field_value)) ? $p_field_value : $p_field_value_default;
				$field_valid = sanitize_text_field($field_valid);
			}
			elseif($p_field_slug=='bgcolor')
			{
				//	bgcolor is string
				$field_valid = (!empty($p_field_value) && is_string($p_field_value)) ? $p_field_value : $p_field_value_default;
				$field_valid = sanitize_text_field($field_valid);
			}
			elseif($p_field_slug=='url')
			{
				//	url is string
				$field_valid = (!empty($p_field_value) && is_string($p_field_value)) ? $p_field_value : $p_field_value_default;
				$field_valid = sanitize_text_field($field_valid);
			}
			elseif($p_field_slug=='new_window')
			{
				//	new_window is numeric
				$field_valid = (!empty($p_field_value) && is_numeric($p_field_value)) ? $p_field_value : $p_field_value_default;
				$field_valid = sanitize_text_field($field_valid);
			}
			elseif($p_field_slug=='alt')
			{
				//	alt is string
				$field_valid = (!empty($p_field_value) && is_string($p_field_value)) ? $p_field_value : $p_field_value_default;
				$field_valid = sanitize_text_field($field_valid);
			}
			
			return $field_valid;
		}
	}
}
