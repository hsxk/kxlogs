<?php
/**
 * Kaya QR Code Generator Metabox.
 *
 * The Kaya QR Code metabox display the Shortcode generator assistant to the admin interface.
 * The Shortcode generator assistant is available on pages, posts, WooCommerce products and all other public custom post types.
 */

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly
}

/**
 * Calls the QRCodeMetaBox Metabox class on the page edit screen.
 */
if (!function_exists('wpkqcg_call_Metabox_qrcodemetabox'))
{
	function wpkqcg_call_Metabox_qrcodemetabox()
	{
		return new WPKQCG_Metabox_qrcodemetabox();
	}
	if (is_admin())
	{
		add_action('load-post.php', 'wpkqcg_call_Metabox_qrcodemetabox');
		add_action('load-post-new.php', 'wpkqcg_call_Metabox_qrcodemetabox');
	}
}

/** 
 * QRCodeMetaBox Metabox Class.
 *
 * @class WPKQCG_Metabox_qrcodemetabox
 */
if (!class_exists('WPKQCG_Metabox_qrcodemetabox'))
{
	class WPKQCG_Metabox_qrcodemetabox
	{
		/**
		 * Construct the Metabox and Hook into the appropriate actions.
		 */
		public function __construct()
		{
			add_action('add_meta_boxes', array(&$this, 'add_page_meta_box'));
		}
		
		/**
		 * Adds the meta box container.
		 */
		public function add_page_meta_box()
		{
			// Get all public post types
			$postTypes = wpkqcg_getAllPostTypesAsList();
			
			add_meta_box(
				'wpkqcg-page-meta-box-qrcodemetabox'
				,esc_html__('Kaya QR Code Generator', WPKQCG_TEXT_DOMAIN)
				,array(&$this, 'render_meta_box_content')
				,$postTypes
				,'normal'
				,'high'
				,null
			);
		}
		
		/**
		 * Render Meta Box content.
		 *
		 * @param WP_Post $post The post object.
		 */
		public function render_meta_box_content($post)
		{
			// get form fields and default values
			$formFieldsHTML = WPKQCG_Forms_QRCode::display_form_fields_options();
			$formFieldsDefaultValues = WPKQCG_Forms_QRCode::get_fields_default_value();
			
			// shortcode preparation
			$shortcodeGenerated = '[kaya_qrcode';
			foreach ($formFieldsDefaultValues as $i_attr => $i_val)
			{
				if ('' != $i_val)
				{
					$shortcodeGenerated .= ' ' . $i_attr . '="' . $i_val . '"';
				}
			}
			$shortcodeGenerated .= ']';
			
			// img preview preparation
			$currentPostID = isset( $_GET['post'] ) ? $_GET['post'] : ( isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : false );
			$currentPostPermalink = (!empty($currentPostID) ? get_permalink($currentPostID) : '');
			$currentAdminURL = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$formFieldsValidated = WPKQCG_Forms_QRCode::validate_fields($formFieldsDefaultValues);
			
			// shortcode html
			$qrcodeShortcodeHTML = '<input type="hidden" id="wpkqcg_shortcode_generator_sc_name" value="kaya_qrcode" />';
			$qrcodeShortcodeHTML .= '<input type="hidden" id="wpkqcg_shortcode_generator_sc_name_dynamic" value="kaya_qrcode_dynamic" />';
			$qrcodeShortcodeHTML .= '<input type="hidden" id="wpkqcg_shortcode_generator_fields_default" value="' . esc_attr(json_encode($formFieldsDefaultValues)) . '" />';
			$qrcodeShortcodeHTML .= '<div><code id="wpkqcg_shortcode_generator_display" style="display:block;border:1px solid #ccc;padding:16px 32px;" >' . esc_html($shortcodeGenerated) . '</code></div>';
			
			// img html
			$qrcodeImgHTML = '<input type="hidden" id="wpkqcg_shortcode_generator_content_post_id" value="' . esc_attr($currentPostID) . '" />';
			$qrcodeImgHTML .= '<input type="hidden" id="wpkqcg_shortcode_generator_content_url_admin" value="' . esc_attr(esc_url($currentAdminURL)) . '" />';
			$qrcodeImgHTML .= '<input type="hidden" id="wpkqcg_shortcode_generator_content_url_default" value="' . esc_attr(esc_url($currentPostPermalink)) . '" />';
			$qrcodeImgHTML .= '<div id="wpkqcg_shortcode_generator_preview_img">';
			$qrcodeImgHTML .= '<div>' . wpkqcg_doDisplayQRCode($formFieldsValidated) . '</div>';
			$qrcodeImgHTML .= '<div>';
			$qrcodeImgHTML .= '<button type="button" onclick="wpkqcg_qrcode_preview_download();" id="wpkqcg_shortcode_generator_preview_open" class="components-button editor-post-preview is-button is-default is-large">' . esc_html__('Download QR Code', WPKQCG_TEXT_DOMAIN) . '</button>';
			$qrcodeImgHTML .= '</div></div>';
			
			// alert permalink html
			$qrcodeAlertHTML = '<div id="wpkqcg_shortcode_generator_preview_permalink_alert" style="display:none;color:#000;background-color:#ddffff;border:1px solid #ccc;padding:16px 32px;">';
			$qrcodeAlertHTML .= esc_html__('The post must be saved for a QR Code preview with the permalink as content.', WPKQCG_TEXT_DOMAIN);
			$qrcodeAlertHTML .= '</div>';
			
			// alert dynamic html
			$qrcodeAlertHTML .= '<div id="wpkqcg_shortcode_generator_preview_dynamic_alert" style="display:none;color:#000;background-color:#ddffff;border:1px solid #ccc;padding:16px 32px;">';
			$qrcodeAlertHTML .= esc_html__('The QR Code preview is not available with a dynamic content.', WPKQCG_TEXT_DOMAIN);
			$qrcodeAlertHTML .= '</div>';
			
			// set metabox HTML content
			$output = '<table class="form-table"><tbody>';
			$output .= '<tr><th>' . esc_html__('Shortcode Generated:', WPKQCG_TEXT_DOMAIN) . '</th></tr>';
			$output .= '<tr><td>' . $qrcodeShortcodeHTML . '</td></tr>';
			$output .= '<tr><th>' . esc_html__('QR Code Preview:', WPKQCG_TEXT_DOMAIN) . '</th></tr>';
			$output .= '<tr><td>' . $qrcodeImgHTML . $qrcodeAlertHTML . '</td></tr>';
			$output .= '<tr><th>' . esc_html__('QR Code settings:', WPKQCG_TEXT_DOMAIN) . '</th></tr>';
			$output .= '<tr><td>' . $formFieldsHTML . '</td></tr>';
			$output .= '</tbody></table>';
			
			// display metabox HTML content
			echo $output;
		}
	}
}
