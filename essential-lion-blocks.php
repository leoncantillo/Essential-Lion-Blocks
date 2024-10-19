<?php
/**
 * Plugin Name:  Essential Lion Blocks
 * Description:  Plugin to display custom blocks with shortcodes
 * Version:      1.3
 * Author:       Leon Cantillo
 * Author URI:   https://github.com/LeonCantillo
 * License:      MIT
 * License URI:  https://opensource.org/licenses/MIT
 * Text Domain:  Essential-Blocks
 * Domain Path:  /languages
 * Charset:      UTF-8
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Enqueue scripts and styles
require_once plugin_dir_path(__FILE__) . 'includes/cpd-ajax-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/enqueue-scripts.php';

// Shortcodes
require_once plugin_dir_path(__FILE__) . 'public/shortcodes/carousel_posts_shortcode.php';
require_once plugin_dir_path(__FILE__) . 'public/shortcodes/custom_post_display_shortcode.php';
require_once plugin_dir_path(__FILE__) . 'public/shortcodes/author_image_shortcode.php';

// Admin functions (if any)
require_once plugin_dir_path(__FILE__) . 'admin/admin-functions.php';

// Plugin update checker master library
require 'plugin-update-checker-master/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/LeonCantillo/Essential-Lion-Blocks/',
	__FILE__,
	'essential-lion-blocks'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

//Optional: If you're using a private repository, specify the access token like this:
// $myUpdateChecker->setAuthentication('your-token-here');