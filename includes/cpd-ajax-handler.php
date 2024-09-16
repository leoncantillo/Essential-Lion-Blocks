<?php

if (!defined('ABSPATH')) {
    exit;
}

function cpd_load_more_posts() {
    $paged = intval($_GET['page']);
    $posts_per_page = intval($_GET['posts_per_page']);
    $post_type = sanitize_text_field($_GET['post_type']);
    $taxonomy = sanitize_text_field($_GET['taxonomy']);
    $term = sanitize_text_field($_GET['term']);
    $show_title = sanitize_text_field($_GET['show_title']);
    $show_excerpt = sanitize_text_field($_GET['show_excerpt']);
    $show_author = sanitize_text_field($_GET['show_author']);
    $show_read_more = sanitize_text_field($_GET['show_read_more']);
	$image_height = sanitize_text_field($_GET['image_height']);

    $query_args = array(
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'paged' => $paged
    );

    if ( !empty($taxonomy) && !empty($term) ) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $term,
            ),
        );
    }

    $query = new WP_Query( $query_args );

    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) : $query->the_post();
            ?>
            <div class="cpd-post-item">
				<?php if ( has_post_thumbnail() ) : ?>
                    <div class="cpd-post-image" style="height: <?php echo esc_attr($image_height); ?>;">
                        <?php the_post_thumbnail(); ?>
                    </div>
                <?php endif; ?>
				
				<div class="cpd-post-infocontainer">
					<?php if ( 'true' === $show_title ) : ?>
						<h2 class="cpd-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php endif; ?>
					
					<?php if ( 'true' === $show_excerpt ) : ?>
						<div class="cpd-post-excerpt"><?php the_excerpt(); ?></div>
					<?php endif; ?>
					
					<?php if ( 'true' === $show_author ) : ?>
						<div class="cpd-post-author">Autor: <?php the_author(); ?></div>
					<?php endif; ?>
					
					<?php if ( 'true' === $show_read_more ) : ?>
						<a class="cpd-read-more" href="<?php the_permalink(); ?>">Leer más</a>
					<?php endif; ?>
				</div>
            </div>
            <?php
        endwhile;
    endif;

    wp_die();
}
add_action( 'wp_ajax_cpd_load_more', 'cpd_load_more_posts' );
add_action( 'wp_ajax_nopriv_cpd_load_more', 'cpd_load_more_posts' );
