<div class="related-posts">
    <h2><?php echo $args['title'] ?? 'Если статья была полезной, загляни в мои другие статьи:'; ?></h2>
    <div class="post-list">
        <?php
        $random_posts = new WP_Query(array(
            'posts_per_page' => 2,
            'post_type' => 'post',
            'orderby' => 'rand',
            'post__not_in' => array(get_the_ID()),
        ));

        if ($random_posts->have_posts()) :
            while ($random_posts->have_posts()) : $random_posts->the_post();
                ?>
                <a href="<?php the_permalink(); ?>" class="post-item">
                    <div class="post-thumbnail">
                        <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('thumbnail');
                        } else {
                            echo '<img src="' . get_template_directory_uri() . '/assets/images/default-avatar.png" alt="Default thumbnail">';
                        }
                        ?>
                    </div>
                    <div class="post-content">
                        <h3 class="post-title"><?php the_title(); ?></h3>
                        <div class="post-meta">
                            <span class="text-muted fw-light"><?php echo get_the_date(); ?></span>
                            <!-- Теги статьи -->
                            <?php
                            $tags = get_the_tags();
                            if ($tags) :
                                foreach ($tags as $tag) : ?>
                                    <span class="badge bg-opacity-10 text-secondary fw-light"><span class="text-danger">#</span><?php echo $tag->name; ?></span>
                                <?php endforeach;
                            endif; ?>
                        </div>
                    </div>
                </a>
            <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
</div>