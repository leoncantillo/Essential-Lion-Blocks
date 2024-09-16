<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Carousel Post Shortcode
function carousel_posts_enqueue_assets() {
    // Enqueue Owl Carousel styles and script
    wp_enqueue_style('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css');
    wp_enqueue_style('owl-theme-default', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css');
    wp_enqueue_script('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array('jquery'), '2.3.4', true);
    
    // Inline script to initialize Owl Carousel
    wp_add_inline_script('owl-carousel', '
        jQuery(document).ready(function($) {
            $(".owl-carousel").each(function() {
                var $carousel = $(this);
                var showNav = $carousel.data("show-nav") === "true";
                var showDots = $carousel.data("show-dots") === "true";
                $carousel.owlCarousel({
                    items: 5,
                    loop: true,
                    margin: 10,
                    autoplay: true,
                    autoplayTimeout: 3000,
                    autoplayHoverPause: true,
                    nav: true,
                    dots: false,
                    navText: ["<div class=\"nav-button owl-prev fa-solid fa-chevron-left\"></div>", "<div class=\"nav-button owl-next fa-solid fa-chevron-right\"></div>"],
                    responsive: {
                        0: {
                            items: 1
                        },
                        480: {
                            items: 3
                        },
                        778: {
                            items: 4
                        },
                        1000: {
                            items: 5
                        }
                    }
                });
            });
        });
    ');

    // Enqueue custom styles
    // wp_enqueue_style('carousel-custom-styles', plugin_dir_url(__FILE__) . '../public/css/custom-styles.css');
    wp_enqueue_style('carousel-custom-styles1', plugin_dir_url(__FILE__) . '../public/css/carousel-styles-1.css');
    wp_enqueue_style('carousel-custom-styles2', plugin_dir_url(__FILE__) . '../public/css/carousel-styles-2.css');
}
add_action('wp_enqueue_scripts', 'carousel_posts_enqueue_assets');

// Custom Post Display Shortcode
function cpd_enqueue_scripts() {
    wp_enqueue_style( 'cpd-style', plugins_url( '../public/css/custom-post-display-styles.css', __FILE__ ) );
    wp_enqueue_script( 'cpd-script', plugins_url( '../public/js/cpd-ajax-load-posts.js', __FILE__ ), array('jquery'), null, true );
    // Pasar datos a JavaScript
    wp_localize_script( 'cpd-script', 'cpd_vars', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
    ));
}
add_action( 'wp_enqueue_scripts', 'cpd_enqueue_scripts' );