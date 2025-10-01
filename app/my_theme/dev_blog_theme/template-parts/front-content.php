<!-- Панель сортировки -->
<div class="sort-panel">
    <nav class="sort-nav">
        <a href="?sort=latest" class="sort-link <?php echo (!isset($_GET['sort']) || $_GET['sort'] === 'latest') ? 'active' : ''; ?>">
            Новые
        </a>
        <a href="?sort=oldest" class="sort-link <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'oldest') ? 'active' : ''; ?>">
            Старые
        </a>
        <a href="?sort=views" class="sort-link <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'views') ? 'active' : ''; ?>">
            Популярные
        </a>
    </nav>
</div>

<?php

// Определяем текущую страницу пагинации
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Определяем параметры сортировки
$args = array(
    'posts_per_page' => get_option('posts_per_page'),
    'paged' => $paged // Добавляем параметр paged для пагинации
);

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';

switch ($sort) {
    case 'latest':
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
        break;
    case 'oldest':
        $args['orderby'] = 'date';
        $args['order'] = 'ASC';
        break;
    case 'views':
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = 'post_views_count';
        $args['order'] = 'DESC';
        break;
    default:
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
        break;
}

// Создаем новый запрос
$query = new WP_Query($args);
?>

<!-- Основной контейнер для статей -->
<div class="articles">
    <!-- Сетка статей: 1 колонка на мобильных, 2 колонки на десктопах -->
    <div class="row row-cols-1 row-cols-md-2 g-3">
        <?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>
            <!-- Колонка для отдельной статьи -->
            <div class="col">
                <!-- Карточка статьи -->
                <article class="card h-100 border-0">
                        <div class="position-relative">
                            <!-- Изображение статьи (если есть) -->
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="card-img-wrapper text-decoration-none">
                                    <img src="<?php the_post_thumbnail_url('large'); ?>"
                                         class="card-img-top"
                                         alt="<?php the_title(); ?>">
                                </a>
                            <?php else: ?>
                                <a href="<?php the_permalink(); ?>"
                                   class="card-img-wrapper text-decoration-none">
                                    <div class="placeholder-img"
                                         style="background-image: url('data:image/svg+xml,<?php echo rawurlencode(get_random_pattern()); ?>')"></div>
                                </a>
                            <?php endif; ?>
                            <!-- Счетчики просмотров -->
                            <?php if (is_user_logged_in()) : ?>
                                <div class="position-absolute top-0 end-0 p-2">
                                    <div class="d-flex gap-2">
                                        <small class="text-white bg-dark bg-opacity-50 px-2 py-1 rounded">
                                            <i class="bi bi-star"></i>
                                            <?php echo get_unique_post_views(get_the_ID()); ?>
                                        </small>
                                        <small class="text-white bg-dark bg-opacity-50 px-2 py-1 rounded">
                                            <i class="bi bi-book"></i>
                                            <?php echo get_post_views(get_the_ID()) ?: rand(100, 200); ?>
                                        </small>
                                    </div>
                                </div>
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
        <?php endwhile; endif; ?>

    </div>

    <!-- Навигация по страницам -->
    <nav class="pagination-wrapper">
        <div class="pagination justify-content-center mt-4" role="navigation" aria-label="Постраничная навигация">
            <?php
            echo paginate_links(array(
                'prev_text' => '<span aria-hidden="true">←</span><span class="visually-hidden">Предыдущая</span>',
                'next_text' => '<span aria-hidden="true">→</span><span class="visually-hidden">Следующая</span>',
                'total' => $query->max_num_pages,
                'current' => max(1, get_query_var('paged')),
                'type' => 'plain',
                'mid_size' => 1,
                'end_size' => 1,
            ));
            ?>
        </div>
    </nav>

</div>

<?php wp_reset_postdata(); // Сбрасываем запрос ?>