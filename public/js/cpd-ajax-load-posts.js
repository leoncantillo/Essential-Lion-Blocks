jQuery(document).ready(function($) {
    var page = 1;
    $(document).on('click', '.cpd-show-more', function() {
        var $button = $(this);
        var originalText = $button.text(); 

        // Cambiar el texto del botón a "Cargando..."
        $button.text('Cargando...');

        page++;
        var $grid = $('.cpd-posts-grid');
        var postsPerPage = $grid.data('posts');
        var imageHeight = $grid.data('image-height');

        $.ajax({
            url: cpd_vars.ajax_url, // Usar la URL de AJAX localizada
            type: 'GET',
            data: {
                action: 'cpd_load_more',
                page: page,
                posts_per_page: postsPerPage,
                post_type: $grid.data('post-type'),
                taxonomy: $grid.data('taxonomy'),
                term: $grid.data('term'),
                show_title: $grid.data('show-title'),
                show_excerpt: $grid.data('show-excerpt'),
                show_author: $grid.data('show-author'),
                show_read_more: $grid.data('show-read-more'),
                image_height: imageHeight
            },
            success: function(response) {
                if (response) {
                    $grid.append(response);
                    $button.text(originalText);
                } else {
                    $button.hide();
                }
            },
            error: function() {
                $button.text(originalText);
            }
        });
    });
});
