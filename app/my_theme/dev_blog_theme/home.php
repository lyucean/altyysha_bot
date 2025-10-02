<?php get_header(); ?>

<!-- Заголовок блога -->
<section class="blog-hero-section py-5">
    <div class="container">
        <div class="text-center">
            <span class="section-badge">
                <i class="fas fa-pen-fancy me-2"></i>Мои мысли
            </span>
            <h1 class="section-title">Блог Дашеньки</h1>
            <p class="section-subtitle">
                Здесь я делюсь своими мыслями о коде, жизни и всём на свете
            </p>
        </div>
    </div>
</section>

<!-- Список постов -->
<section class="blog-posts-section py-5">
    <div class="container">
        <div class="row">
            <?php
            // Получаем посты с пагинацией
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

            $posts_query = new WP_Query(array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 6, // 6 постов на страницу
                'paged' => $paged
            ));

            if ($posts_query->have_posts()) :
                while ($posts_query->have_posts()) : $posts_query->the_post();
                    ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <article class="blog-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="blog-card-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                    </a>
                                    <div class="blog-card-date">
                                        <?php echo get_the_date('d M'); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="blog-card-content">
                                <div class="blog-card-meta">
                                <span class="blog-category">
                                    <?php
                                    $categories = get_the_category();
                                    if (!empty($categories)) {
                                        echo esc_html($categories[0]->name);
                                    }
                                    ?>
                                </span>
                                    <span class="blog-reading-time">
                                    <i class="fas fa-clock"></i>
                                    <?php echo ceil(str_word_count(get_the_content()) / 200); ?> мин
                                </span>
                                </div>

                                <h3 class="blog-card-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>

                                <p class="blog-card-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                </p>

                                <div class="blog-card-footer">
                                    <a href="<?php the_permalink(); ?>" class="btn-nature-small">
                                        Читать далее
                                        <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php
                endwhile;
            else :
                ?>
                <div class="col-12 text-center">
                    <div class="no-posts-message">
                        <i class="fas fa-feather-alt mb-3"></i>
                        <h3>Пока что здесь пусто</h3>
                        <p>Но скоро появятся интересные посты!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Пагинация -->
        <?php if ($posts_query->max_num_pages > 1) : ?>
            <div class="row mt-5">
                <div class="col-12">
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
            </div>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</section>

<?php get_footer(); ?>
