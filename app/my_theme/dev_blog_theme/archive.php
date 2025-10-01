<?php get_header(); ?>

<div class="archive-page container-xl">
<div class="row ">
    <!-- Левая боковая панель -->
    <aside class="col-lg-1 d-none d-lg-block">
        <div class="sticky-top d-flex flex-column align-items-end">
            <a href="/" class="btn rounded-3 p-2 fs-4 border-0 shadow-none" title="На главную">
                <i class="bi bi-house"></i>
            </a>
            <a href="javascript:history.back()" class="btn rounded-3 p-2 fs-4 border-0 shadow-none" title="Назад">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </aside>
    <!-- Основное содержимое -->
    <main class="col-12 col-lg-11">
        <!-- Заголовок архива -->
        <div class="archive-header mb-4">
            <div class="archive-title px-3 px-md-0">
                <h1 class="h2 d-inline-flex align-items-baseline gap-2">
                    <?php
                    if (is_category()) {
                        echo '<span class="fw-bold">' . single_cat_title('', false) . '</span>';
                    } elseif (is_tag()) {
                        echo '<span class="fw-light"><span class="text-danger">#</span>' . single_tag_title('', false) . '</span>';
                    } elseif (is_author()) {
                        echo '<span class="text-body-secondary fw-normal fs-5">Автор</span>
                  <span class="text-primary-emphasis fw-bold">' . get_the_author() . '</span>';
                    } elseif (is_date()) {
                        echo '<span class="text-body-secondary fw-normal fs-5">Архив за</span>
                  <span class="text-primary-emphasis fw-bold">';
                        if (is_day()) {
                            echo get_the_date();
                        } elseif (is_month()) {
                            echo get_the_date('F Y');
                        } elseif (is_year()) {
                            echo get_the_date('Y');
                        }
                        echo '</span>';
                    }
                    ?>
                </h1>
            </div>


            <?php
            // Описание категории/тега если есть
            $description = get_the_archive_description();
            if ($description) : ?>
                <div class="archive-description text-muted mb-4 px-3 px-md-0">
                    <?php echo $description; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (have_posts()) : ?>
            <div class="row g-4">
                <?php while (have_posts()) : the_post(); ?>
                    <div class="col-md-6 col-lg-4">
                        <!-- Карточка статьи -->
                        <article class="card h-100 border-0">
                            <!-- Обертка изображения -->
                            <div class="card-img-wrapper">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="card-img-wrapper text-decoration-none">
                                        <img src="<?php the_post_thumbnail_url('large'); ?>"
                                             class="card-img-top"
                                             alt="<?php the_title(); ?>">
                                    </a>
                                <?php else: ?>
                                    <a href="<?php the_permalink(); ?>" class="card-img-wrapper text-decoration-none">
                                        <div class="placeholder-img" style="background-image: url('data:image/svg+xml,<?php echo rawurlencode(get_random_pattern()); ?>')"></div>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <!-- Основное содержимое карточки -->
                            <div class="card-body p-3 d-flex flex-column">
                                <!-- Заголовок статьи -->
                                <h2 class="h5 fw-bold mb-3">
                                    <a href="<?php the_permalink(); ?>" class="text-body-emphasis text-decoration-none">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <!-- Описание статьи -->
                                <p class="card-text mb-3">
                                    <?php
                                    $yoast_meta = get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true);
                                    if (!empty($yoast_meta)) {
                                        echo wp_trim_words($yoast_meta, 20);
                                    } else {
                                        echo wp_trim_words(get_the_excerpt(), 20);
                                    }
                                    ?>
                                </p>

                                <!-- Нижняя часть карточки с метаданными -->
                                <div class="mt-auto">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                                        <!-- Блок с датой и тегами -->
                                        <div class="d-flex flex-wrap gap-0 align-items-center">
                                            <!-- Дата публикации -->
                                            <small class="text-muted fw-light"><?php echo get_the_date(); ?></small>
                                            <!-- Теги статьи -->
                                            <?php
                                            $tags = get_the_tags();
                                            if ($tags) :
                                                foreach ($tags as $tag) : ?>
                                                    <a href="<?php echo get_tag_link($tag->term_id); ?>" class="text-decoration-none">
                                                        <span class="badge bg-opacity-10 text-secondary fw-light">
                                                            <span class="text-danger">#</span><?php echo $tag->name; ?>
                                                        </span>
                                                    </a>
                                                <?php endforeach;
                                            endif; ?>
                                        </div>

                                        <!-- Время чтения -->
                                        <small class="text-muted fw-light">
                                            <i class="bi bi-clock-history"></i>
                                            <?php echo get_reading_time(get_the_content()) . ' мин чтения'; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="mt-4">
                <!-- Навигация по страницам -->
                <nav class="pagination-wrapper">
                    <div class="pagination justify-content-center mt-4" role="navigation" aria-label="Постраничная навигация">
                        <?php
                        global $wp_query;
                        echo paginate_links(array(
                            'prev_text' => '<span aria-hidden="true">←</span><span class="visually-hidden">Предыдущая</span>',
                            'next_text' => '<span aria-hidden="true">→</span><span class="visually-hidden">Следующая</span>',
                            'total' => $wp_query->max_num_pages,
                            'current' => max(1, get_query_var('paged')),
                            'type' => 'plain',
                            'mid_size' => 1,
                            'end_size' => 1,
                            'base' => str_replace(999999999, '%#%', get_pagenum_link(999999999)),
                            'format' => '?paged=%#%',
                        ));
                        ?>
                    </div>
                </nav>
            </div>

        <?php else : ?>
            <div class="alert alert-info">
                <h3>Записей не найдено</h3>
                <p>В этом разделе пока нет записей. Вернуться на <a href="<?php echo home_url(); ?>">главную страницу</a>.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>

<?php get_footer(); ?>
