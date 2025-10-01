<?php get_header(); ?>

<div class="container-fluid single-post">
    <div class="row">
        <!-- Левая боковая панель -->
        <aside class="col-lg-1 d-none d-lg-block">
            <div class="sticky-top d-flex flex-column align-items-end">
                <a href="/" class="btn rounded-3 p-2 fs-4 border-0 shadow-none" title="На главную">
                    <i class="bi bi-house"></i>
                </a>
                <?php
                // Кнопка вернутся назад
                // Получим HTTP_REFERER, что понять откуда к нам пришел пользователь
                $referer = $_SERVER['HTTP_REFERER'] ?? '';
                // Проверяем, содержит ли HTTP_REFERER URL нашего сайта, если да, то показываем кнопку
                if (!empty($referer) && strpos($referer, parse_url(get_site_url(), PHP_URL_HOST)) !== false) :
                    ?>
                    <a href="javascript:history.back()" class="btn rounded-3 p-2 fs-4 border-0 shadow-none" title="Назад">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                <?php endif; ?>

            </div>
        </aside>

        <!-- Основное содержимое -->
        <main class="col-12 col-lg-10">
            <article class="rounded mb-4"><!-- Убрали класс bg-white -->

                <!-- Шапка статьи с изображением -->
                <div class="card border-0">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="header-image-container rounded-top">
                            <?php the_post_thumbnail('large', ['class' => 'card-img rounded-top']); ?>
                            <div class="header-blur-overlay"></div>
                            <div class="card-img-overlay d-flex flex-column justify-content-end">
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <span class="text-white-50"><?php echo get_the_date(); ?></span>

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

                                    <?php
                                    $tags = get_the_tags();
                                    if ($tags) :
                                        foreach ($tags as $tag) : ?>
                                            <a href="<?php echo get_tag_link($tag->term_id); ?>"
                                               class="text-decoration-none">
                                                <span class="badge bg-light bg-opacity-10 text-white fw-light">
                                                    <span class="text-danger">#</span><?php echo $tag->name; ?>
                                                </span>
                                            </a>
                                        <?php endforeach;
                                    endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <!-- Если нет изображения, показываем обычный заголовок -->
                        <div class="p-4">
                            <h1 class="h2 fw-bold text-center mb-3"><?php the_title(); ?></h1>
                            <div class="text-center mb-4">
                                <div class="d-inline-flex flex-wrap align-items-center">
                                    <span class="text-secondary fs-6 me-2"><?php echo get_the_date(); ?></span>
                                    <?php
                                    if ($tags) :
                                        foreach ($tags as $tag) : ?>
                                            <a href="<?php echo get_tag_link($tag->term_id); ?>"
                                               class="tag-link text-decoration-none">
                                            <span class="badge bg-opacity-10 text-secondary fs-6 fw-light">
                                                <span class="text-danger">#</span><?php echo $tag->name; ?>
                                            </span>
                                            </a>
                                        <?php endforeach;
                                    endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Содержимое статьи -->
                <div class="article-content">
                    <h1 class=" "><?php the_title(); ?></h1>
                    <?php the_content(); ?>
                </div>
            </article>

            <!-- Блок со случайными статьями -->
            <?php get_template_part('template-parts/posts', 'related'); ?>

        </main>

        <!-- Правая боковая панель -->
        <aside class="col-lg-1 d-none d-lg-block">
            <div class="sticky-bottom d-flex align-items-start">
                <button id="scrollTopBtn" class="btn rounded-3 p-2 fs-4 border-0 shadow-none opacity-0"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#"
                        aria-expanded="false"
                        title="Наверх">
                    <i class="bi bi-arrow-up"></i>
                </button>
            </div>
        </aside>

    </div>
</div>

<?php get_footer(); ?>
