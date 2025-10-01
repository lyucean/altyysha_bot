<?php get_header(); ?>

<div class="container-fluid page">
    <div class="row">
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
        <main class="col-12 col-lg-10">
            <!-- Если нет изображения, показываем обычный заголовок -->
            <?php if (!has_post_thumbnail()) : ?>
                <div class="row">
                    <h1 class="h1 fw-bold text-center mb-3"><?php the_title(); ?></h1>
                </div>
            <?php endif; ?>
            <article class="rounded mb-4">
                <!-- Шапка страницы с изображением -->
                <div class="card border-0">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="header-image-container rounded-top">
                            <?php the_post_thumbnail('large', ['class' => 'card-img rounded-top']); ?>
                            <div class="header-blur-overlay"></div>
                            <div class="card-img-overlay d-flex flex-column justify-content-end">
                                <h1 class="card-title text-white text-center mb-3"><?php the_title(); ?></h1>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Содержимое страницы -->
                <div class="article-content">
                    <?php the_content(); ?>
                </div>

            </article>
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
