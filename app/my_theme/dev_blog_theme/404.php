<?php get_header(); ?>

<div class="container-fluid page">
    <div class="row">
        <!-- Основное содержимое -->
        <main class="col-12 col-lg-10 offset-lg-1 px-lg-5">
            <article class="rounded mb-4">
                <!-- Содержимое страницы -->
                <div class="article-content">

                    <h1 class="h1 fw-bold text-center mb-3">Ошибка 404</h1>

                    <!-- Видео контейнер -->
                    <div class="video-wrapper mb-4 position-relative d-grid gap-3 col-8 mx-auto">
                        <div class="custom-video-container rounded-0 overflow-hidden">
                            <video
                                    width="100%"
                                    height="auto"
                                    autoplay
                                    muted
                                    loop
                                    playsinline
                                    controls
                                    poster="<?php echo get_template_directory_uri(); ?>/images/404.gif"
                                    class="rounded-4"
                            >
                                <source src="<?php echo get_template_directory_uri(); ?>/video/404.mp4" type="video/mp4">
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-video me-2"></i>Ваш браузер не поддерживает видео.
                                </div>
                            </video>
                        </div>
                    </div>

                    <p class="lead mb-4 text-center">У нас что-то происходит, но мы пока не знаем что именно.</p>

                    <div class="d-grid gap-2 col-6 mx-auto">
                        <a href="<?php echo home_url(); ?>" class="btn btn-warning">
                            <i class="fas fa-home me-2"></i>Кликни меня, помогу как смогу
                        </a>
                    </div>
                </div>

            </article>

            <!-- Блок с рекомендованными статьями -->
            <?php get_template_part('template-parts/posts', 'related', [
                    'title' => 'Пока вы здесь, почитайте что-нибудь интересное:'
            ]); ?>

        </main>
    </div>
</div>

<script>
    setTimeout(function() {
        ym(89564639,'reachGoal','404');
    }, 1000);
</script>

<?php get_footer(); ?>
