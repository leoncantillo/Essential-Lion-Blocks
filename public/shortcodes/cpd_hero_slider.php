<?php
// Author Image Shortcode

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function cpd_hero_slider_shortcode($atts) {

    $atts = shortcode_atts([
        'category' => '',
        'posts'    => 5,
    ], $atts);

    if (empty($atts['category'])) {
        return '';
    }

    $query = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => intval($atts['posts']),
        'category_name'  => sanitize_text_field($atts['category']),
    ]);

    if (!$query->have_posts()) {
        return '';
    }

    ob_start(); ?>

    <div class="cpd-hero-slider">

        <?php while ($query->have_posts()) : $query->the_post();

            $image = get_the_post_thumbnail_url(get_the_ID(), 'full');
            if (!$image) continue;

            // Custom field para posición Y
            $pos_y = get_post_meta(get_the_ID(), 'hero_bg_position_y', true);
            $pos_y = $pos_y ? esc_attr($pos_y) : '50%';

			$title = wp_strip_all_tags(get_the_title());

        ?>

            <article class="cpd-hero-slide"
                style="background-image:url('<?php echo esc_url($image); ?>');
                       background-position: center <?php echo $pos_y; ?>;">

                <div class="cpd-hero-overlay alignwide">
                    <h2><?php echo esc_html($title); ?></h2>
                </div>

            </article>

        <?php endwhile; wp_reset_postdata(); ?>

    </div>

    <?php
    return ob_get_clean();
}

add_shortcode('cpd_hero_slider', 'cpd_hero_slider_shortcode');
