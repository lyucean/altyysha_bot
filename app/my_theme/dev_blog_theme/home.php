<?php get_header(); ?>

<!-- Заголовок блога -->
<section class="blog-hero-section py-5">
    <div class="container">
        <div class="text-center">
            <span class="section-badge">
                <i class="fas fa-pen-fancy me-2"></i>Мои мысли
            </span>
            <h1 class="section-title">Блог Дашеньки</h1>
        </div>
    </div>

    <!-- Плавающие элементы -->
    <div class="blog-floating-elements">
        <i class="fas fa-pen-alt floating-pen blog-floating-item"></i>
        <i class="fas fa-book-open floating-book blog-floating-item"></i>
        <i class="fas fa-heart floating-heart blog-floating-item"></i>
        <i class="fas fa-star floating-star blog-floating-item"></i>
        <i class="fas fa-leaf floating-leaf blog-floating-item"></i>
        <i class="fas fa-cloud floating-cloud blog-floating-item"></i>
    </div>

    <!-- Декоративные элементы -->
    <div class="blog-leaf-1 blog-decoration"></div>
    <div class="blog-leaf-2 blog-decoration"></div>
    <div class="blog-sparkle-1 blog-decoration"></div>
    <div class="blog-sparkle-2 blog-decoration"></div>
</section>

<!-- Список постов -->
<section class="blog-posts-section py-5">
    <div class="container">
        <?php
        // Получаем посты с пагинацией
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $posts_query = new WP_Query(array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 9,
                'paged' => $paged
        ));

        if ($posts_query->have_posts()) :
            ?>
            <div class="instagram-grid">
                <?php while ($posts_query->have_posts()) : $posts_query->the_post(); ?>
                    <article class="instagram-post">
                        <div class="post-image-container">
                            <a href="<?php the_permalink(); ?>" class="post-link">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium', array('class' => 'post-image')); ?>
                                <?php else : ?>
                                    <div class="post-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="post-overlay">
                                    <div class="post-meta">
                                        <span class="post-date">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo get_the_date('d.m.Y'); ?>
                                        </span>
                                        <span class="post-reading-time">
                                            <i class="fas fa-clock"></i>
                                            <?php echo ceil(str_word_count(get_the_content()) / 200); ?> мин
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="post-title-container">
                            <h3 class="post-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>

                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) :
                                ?>
                                <span class="post-category">
                                    <?php echo esc_html($categories[0]->name); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <div class="no-posts-message">
                <div class="no-posts-content">
                    <i class="fas fa-feather-alt"></i>
                    <h3>Пока что здесь пусто</h3>
                    <p>Но скоро появятся интересные посты!</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Пагинация -->
        <?php if ($posts_query->max_num_pages > 1) : ?>
            <div class="blog-pagination-wrapper">
                <nav class="blog-pagination">
                    <?php
                    echo paginate_links(array(
                            'total' => $posts_query->max_num_pages,
                            'current' => $paged,
                            'prev_text' => '<i class="fas fa-chevron-left"></i> Назад',
                            'next_text' => 'Вперёд <i class="fas fa-chevron-right"></i>',
                            'type' => 'list'
                    ));
                    ?>
                </nav>
            </div>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>

    <!-- Дополнительные плавающие элементы для секции постов -->
    <div class="blog-floating-elements">
        <i class="fas fa-code floating-code blog-floating-item"></i>
        <i class="fas fa-music floating-music blog-floating-item"></i>
    </div>
</section>

<?php get_footer(); ?>
