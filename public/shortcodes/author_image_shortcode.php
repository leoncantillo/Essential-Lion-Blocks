<?php
// Author Image Shortcode

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function author_image_shortcode($atts) {
    $atts = shortcode_atts(array(
        'height' => '300px',
        'width' => '300px',
        'style' => 1,
    ), $atts, 'author_image');

    $height = sanitize_text_field($atts['height']);
    $width = sanitize_text_field($atts['width']);
    $style = intval($atts['style']);

    $author_id = get_the_author_meta('ID');
    
    $author_image = get_avatar_url($author_id);
    
    return '<img src="' . esc_url($author_image) . '" height="' . esc_attr($height) . '" width="' . esc_attr($width) . '" style="border-radius: ' . esc_attr($style) . 'px;" alt="Imagen del autor" />';
}

add_shortcode('author_image', 'author_image_shortcode');
