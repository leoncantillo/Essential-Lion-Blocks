<?php
/**
 * Plugin Name:  Essential Lion Blocks
 * Description:  Plugin to display custom blocks with shortcodes
 * Version:      1.0
 * Author:       Leon Cantillo
 * Author URI:   https://github.com/LeonCantillo
 * License:      MIT
 * License URI:  https://opensource.org/licenses/MIT
 * Text Domain:  Essential-Blocks
 * Domain Path:  /languages
 * Charset:      UTF-8
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Shortcode
require_once plugin_dir_path(__FILE__) . 'public/shortcodes/carousel_posts_shortcode.php';

// Admin functions (if any)
require_once plugin_dir_path(__FILE__) . 'admin/admin-functions.php';