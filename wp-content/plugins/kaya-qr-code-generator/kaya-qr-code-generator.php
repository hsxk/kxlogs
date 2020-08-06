<?php
/**
 * Plugin Name: Kaya QR Code Generator
 * Description: Generate QR Code through Widgets and Shortcodes, without any dependencies.
 * Version: 1.3.2
 * Author: Kaya Studio
 * Author URI:  https://kayastudio.fr
 * Text Domain: kaya-qr-code-generator
 * Domain Path: /languages
 */

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly
}

if (!defined('WPKQCG_VERSION'))		define('WPKQCG_VERSION', '1.3.2');
if (!defined('WPKQCG_FILE'))		define('WPKQCG_FILE', plugin_basename(__FILE__));
if (!defined('WPKQCG_PLUGIN_URL'))	define('WPKQCG_PLUGIN_URL', plugin_dir_url( __FILE__ ));
if (!defined('WPKQCG_PLUGIN_PATH'))	define('WPKQCG_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
if (!defined('WPKQCG_TEXT_DOMAIN'))	define('WPKQCG_TEXT_DOMAIN', 'kaya-qr-code-generator');

// Include the main functions
require_once(WPKQCG_PLUGIN_PATH . 'lib/functions.php');
// Include the forms class.
require_once(WPKQCG_PLUGIN_PATH . 'lib/class.forms.php');
// Include the QRCode forms class
require_once(WPKQCG_PLUGIN_PATH . 'lib/class.forms_qrcode.php');
// Include the Shortcodes class
require_once(WPKQCG_PLUGIN_PATH . 'lib/class.shortcodes_qrcodeshortcode.php');
// Include the Widgets class
require_once(WPKQCG_PLUGIN_PATH . 'lib/class.widgets_qrcodewidget.php');
// Include the Metabox class
require_once(WPKQCG_PLUGIN_PATH . 'lib/class.metabox_qrcodemetabox.php');

/**
 * Load Localisation files.
 *
 * @since 1.0.1
 */
if (!function_exists('wpkqcg_plugin_loadLocalisation'))
{
	function wpkqcg_plugin_loadLocalisation()
	{
		load_plugin_textdomain(WPKQCG_TEXT_DOMAIN, false, dirname(WPKQCG_FILE) . '/languages/');
	}
	add_action('plugins_loaded', 'wpkqcg_plugin_loadLocalisation');
}

/**
 * Show action links on the plugin screen.
 *
 * @param mixed $links Plugin Action links.
 * @param mixed $file Plugin Base file.
 *
 * @return array
 */
if (!function_exists('wpkqcg_plugin_displayActionLinks'))
{
	function wpkqcg_plugin_displayActionLinks($links, $file)
	{
		if (WPKQCG_FILE === $file)
		{
			$actionLinks = array(
				'ks_plugins' => '<a href="' . esc_url('https://profiles.wordpress.org/kayastudio#content-plugins') . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__('Other plugins by Kaya Studio', WPKQCG_TEXT_DOMAIN).'">' . esc_html__('Other plugins', WPKQCG_TEXT_DOMAIN) . '</a>',
			);
			
			return array_merge($actionLinks, $links);
		}
		
		return (array) $links;
	}
	add_filter('plugin_action_links', 'wpkqcg_plugin_displayActionLinks', 10, 2);
}

/**
 * Show row meta on the plugin screen.
 *
 * @param mixed $links Plugin Row Meta.
 * @param mixed $file Plugin Base file.
 *
 * @return array
 */
if (!function_exists('wpkqcg_plugin_displayMetaLinks'))
{
	function wpkqcg_plugin_displayMetaLinks($links, $file)
	{
		if (WPKQCG_FILE === $file)
		{
			$metaLinks = array(
				'ks_rate'	=> '<a href="' . esc_url('https://wordpress.org/support/plugin/kaya-qr-code-generator/reviews/?rate=5#new-post') . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__('Rate and review this plugin at WordPress.org', WPKQCG_TEXT_DOMAIN) . '">' . esc_html__('Rate this plugin', WPKQCG_TEXT_DOMAIN) . '&nbsp;&#9733;</a>',
				'ks_donate'	=> '<a href="' . esc_url('http://dotkaya.org/a-propos/') . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__('Donate to support the advancement of this plugin', WPKQCG_TEXT_DOMAIN) . '">' . esc_html__('Donate to this plugin', WPKQCG_TEXT_DOMAIN) . '&nbsp;&#9829;</a>',
			);

			return array_merge($links, $metaLinks);
		}
		
		return (array) $links;
	}
	add_filter('plugin_row_meta', 'wpkqcg_plugin_displayMetaLinks', 10, 2);
}
