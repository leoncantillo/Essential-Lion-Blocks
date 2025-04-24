<?php

if (!defined('ABSPATH')) {
    exit;
}

function cpd_display_posts( $atts ) {
    $atts = shortcode_atts(
        array(
			'image_height' => '300px',
            'show_image' => 'true',
            'show_title' => 'true',
            'show_excerpt' => 'true',
            'show_author' => 'true',
            'show_read_more' => 'false',
			'show_category' => 'false',
			'category_link' => 'false',
            'post_type' => 'post',
            'taxonomy' => '',
            'term' => '',
            'posts_per_page' => '6'
        ), $atts, 'custom_posts'
    );

    ob_start();

    $query_args = array(
        'post_type' => $atts['post_type'],
        'posts_per_page' => intval($atts['posts_per_page']),
        'paged' => 1
    );

    if ( !empty($atts['taxonomy']) && !empty($atts['term']) ) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => $atts['taxonomy'],
                'field'    => 'slug',
                'terms'    => $atts['term'],
            ),
        );
    }

    $query = new WP_Query( $query_args );
    $total_posts = $query->found_posts;
    $posts_per_page = intval($atts['posts_per_page']);
    $current_page = get_query_var('paged') ? get_query_var('paged') : 1;
    $has_more_posts = ($total_posts > $posts_per_page * $current_page);

    ?>
    <div class="custom-post-display">
        <div 
            class="cpd-posts-grid"
            data-posts="<?php echo esc_attr($atts['posts_per_page']); ?>"
            data-post-type="<?php echo esc_attr($atts['post_type']); ?>" 
            data-taxonomy="<?php echo esc_attr($atts['taxonomy']); ?>"
            data-term="<?php echo esc_attr($atts['term']); ?>"
            data-show-title="<?php echo esc_attr($atts['show_title']); ?>"
            data-show-excerpt="<?php echo esc_attr($atts['show_excerpt']); ?>"
            data-show-author="<?php echo esc_attr($atts['show_author']); ?>"
            data-show-read-more="<?php echo esc_attr($atts['show_read_more']); ?>"
			data-show-category="<?php echo esc_attr($atts['show_category']); ?>"
			 data-show-category="<?php echo esc_attr($atts['category_link']); ?>"
			data-image-height="<?php echo esc_attr($atts['image_height']); ?>"
        >
            <?php

            if ( $query->have_posts() ) :
                while ( $query->have_posts() ) : $query->the_post();
                    ?>
                    <div class="cpd-post-item">
                         <?php if ( 'true' === $atts['show_image'] && has_post_thumbnail() ) : ?>
							<div class="cpd-post-image" style="height: <?php echo esc_attr($atts['image_height']); ?>;">
								<?php the_post_thumbnail(); ?>
							</div>
						<?php endif; ?>
                        
                        <div class="cpd-post-infocontainer">
							<?php if ('true' === $atts['show_category']) : ?>
								<div class="cpd-post-category">
									<?php
									$category = wp_get_post_terms(get_the_ID(), $atts['taxonomy']);
									if(!empty($category)) {
										if ('true' === $atts['category_link'])
											$category_link = esc_url(get_category_link($category[0]->term_id));
										
										echo '<a href="'. $category_link. '">' .esc_html($category[0]->name). '</a>';
									}
									?>
								</div>
							<?php endif; ?>
							
                            <?php if ( 'true' === $atts['show_title'] ) : ?>
                                <h2 class="cpd-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <?php endif; ?>
                            
                            <?php if ( 'true' === $atts['show_excerpt'] ) : ?>
                                <div class="cpd-post-excerpt"><?php the_excerpt(); ?></div>
                            <?php endif; ?>
                            
                            <?php if ( 'true' === $atts['show_author'] ) : ?>
                                <div class="cpd-post-author">Autor: <?php the_author(); ?></div>
                            <?php endif; ?>
                            
                            <?php if ( 'true' === $atts['show_read_more'] ) : ?>
                                <a class="cpd-read-more" href="<?php the_permalink(); ?>">Leer más</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                endwhile;
            else :
                echo '<div class="cpd-no-posts">No hay posts disponibles.</div>';
            endif;

            wp_reset_postdata();
            ?>
        </div>
        <?php if ($has_more_posts) : ?>
            <div class="cpd-load-more-container">
                <button class="cpd-show-more">Mostrar más</button>
            </div>
        <?php endif; ?>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode( 'custom_posts', 'cpd_display_posts' );
