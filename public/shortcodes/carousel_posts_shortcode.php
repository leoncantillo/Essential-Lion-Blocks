<?php
// Carousel Post Shortcode

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function carousel_posts_shortcode($atts) {
    $atts = shortcode_atts(array(
        'post_type' => 'post',
        'taxonomy' => '',
        'term' => '',
        'posts_per_page' => 5,
        'show_title' => 'true',
        'show_image' => 'true',
        'show_excerpt' => 'true',
        'show_author' => 'true',
        'show_date' => 'true',
        'show_categories' => 'true',
        'show_button' => 'true',
        'show_nav' => 'true',
        'show_dots' => 'true',
        'style' => 1,
        'latest_by_author' => 'false',
        'author_image_as_thumbnail' => 'false',
        'exclude_current' => 'false',
    ), $atts, 'custom_posts_carousel');

    $args = array(
        'post_type' => sanitize_text_field($atts['post_type']),
        'posts_per_page' => intval($atts['posts_per_page']),
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => false,
    );

    if ($atts['exclude_current'] === 'true' && is_single()) {
        $args['post__not_in'] = array(get_the_ID());
    }

    if (!empty($atts['term']) && empty($atts['taxonomy'])) {
        $taxonomies = get_object_taxonomies(sanitize_text_field($atts['post_type']), 'objects');
        foreach ($taxonomies as $taxonomy) {
            $term_exists = term_exists(sanitize_text_field($atts['term']), $taxonomy->name);
            if ($term_exists) {
                $atts['taxonomy'] = $taxonomy->name;
                break;
            }
        }
    }

    if (!empty($atts['taxonomy']) && !empty($atts['term'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => sanitize_text_field($atts['taxonomy']),
                'field' => 'slug',
                'terms' => sanitize_text_field($atts['term']),
            ),
        );
    }

    if ($atts['latest_by_author'] === 'true') {
        $authors = get_users(array(
            'who' => 'authors',
            'has_published_posts' => sanitize_text_field($atts['post_type'])
        ));

        $post_ids = array();
        foreach ($authors as $author) {
            $latest_post = get_posts(array_merge($args, array(
                'author' => $author->ID,
                'posts_per_page' => 1,
                'fields' => 'ids',
            )));
            if (!empty($latest_post)) {
                $post_ids[] = $latest_post[0];
            }
        }

        if (!empty($post_ids)) {
            $args = array(
                'post__in' => $post_ids,
                'orderby' => 'post__in',
                'posts_per_page' => intval($atts['posts_per_page']),
                'post_type' => sanitize_text_field($atts['post_type']),
            );

            if (!empty($atts['taxonomy']) && !empty($atts['term'])) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => sanitize_text_field($atts['taxonomy']),
                        'field' => 'slug',
                        'terms' => sanitize_text_field($atts['term']),
                    ),
                );
            }
        } else {
            $args['posts_per_page'] = 0;
        }
    }

    $query = new WP_Query($args);

    ob_start();
    ?>
    <div class="owl-carousel style-<?php echo esc_attr($atts['style']); ?>" data-show-nav="<?php echo esc_attr($atts['show_nav']); ?>" data-show-dots="<?php echo esc_attr($atts['show_dots']); ?>">
    <?php
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <div class="item">
                <?php 
                if ($atts['show_image'] === 'true') : 
                    if ($atts['author_image_as_thumbnail'] === 'true') {
                        $author_id = get_the_author_meta('ID');
                        $author_image = get_avatar_url($author_id, array('size' => '300'));
                        ?>
                        <div class="author-image">
                            <img src="<?php echo esc_url($author_image); ?>" alt="<?php the_author(); ?>" />
                        </div>
                        <?php
                    } elseif (has_post_thumbnail()) {
                        ?>
                        <div class="thumbnail-image">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                        <?php
                    }
                endif;
                ?>
                
                <?php if ($atts['show_title'] === 'true') : ?>
                    <h2 class="carousel-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php endif; ?>
                
                <?php if ($atts['show_excerpt'] === 'true') : ?>
                    <p class="carousel-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                <?php endif; ?>
                
                <?php if ($atts['show_author'] === 'true') : ?>
                    <p class="carousel-author">
                        <?php _e('Por', 'carousel-posts'); ?> 
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="carousel-author-name">
                            <?php the_author(); ?>
                        </a>
                    </p>
                <?php endif; ?>
                
                <?php if ($atts['show_date'] === 'true' || $atts['show_categories'] === 'true') : ?>
                    <div class="carousel-meta">
                        <?php if ($atts['show_date'] === 'true') : ?>
                            <p class="carousel-date"><?php echo get_the_date(); ?></p>
                        <?php endif; ?>
                        
                        <?php if ($atts['show_categories'] === 'true') : ?>
                            <p class="carousel-category"><?php the_category(', '); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($atts['show_button'] === 'true') : ?>
                    <a class="carousel-button" href="<?php the_permalink(); ?>"><?php _e('Ver más ', 'carousel-posts'); ?> <i class="fa-solid fa-chevron-right"></i></a>
                <?php endif; ?>
            </div>
            <?php
        }
    } else {
        echo '<p>' . __('No posts available.', 'carousel-posts') . '</p>';
    }
    ?>
    </div>
    <?php

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('custom_posts_carousel', 'carousel_posts_shortcode');