<?php get_header(); ?>

<div class="front-page container-xl">
    <div class="row g-4">
        <!-- Левая колонка с десктопным меню -->
        <div class="col-md-2 d-none d-md-block">
            <?php get_template_part('template-parts/front', 'menu-desktop'); ?>
        </div>

        <!-- Правая колонка с контентом -->
        <div class="col-12 col-md-9 mt-1">
            <?php get_template_part('template-parts/front', 'content'); ?>
        </div>

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
