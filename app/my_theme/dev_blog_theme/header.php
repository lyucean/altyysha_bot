<!DOCTYPE html>
<html <?php language_attributes(); ?> lang="ru" data-bs-theme="light">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="yandex-verification" content="7cd6de573f5f2c8b" />
    <title><?php wp_title(); ?></title>
    <?php wp_head(); ?>
</head>
<body class="bg-body-tertiary" <?php body_class(); ?>>

<header class="py-3">
    <div class="container-xl">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <div class="d-flex align-items-center">
                    <!-- Мобильное меню вне колонок -->
                    <button class="btn btn-outline-secondary border-0 d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebarMenu" aria-controls="mobileSidebarMenu" aria-expanded="false">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <?php get_template_part('template-parts/front', 'menu-mobile'); ?>

                    <!-- Логотип -->
                    <a class="navbar-brand fw-bold fs-4 ps-md-4 ms-md-3 logo" href="<?=esc_url(home_url('/'));  ?>">
                        <?=get_bloginfo('name')?>
                    </a>
                </div>
            </div>

            <!-- Поиск (скрыт на мобильных) -->
            <div class="col d-none d-md-block">
                <div class="row justify-content-center">
                    <div class="col-md-7">
                        <form class="d-flex" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 opacity-50">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input class="form-control border-start-0 ps-0 opacity-50"
                                       type="search"
                                       placeholder=" Поищем?"
                                       aria-label="Search"
                                       name="s"
                                       value="<?php echo get_search_query(); ?>">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Иконка поиска (видна только на мобильных) и переключатель темы -->
            <div class="col-auto">
                <div class="d-flex align-items-center gap-4 me-2 me-md-5">
                    <div class="d-md-none">
                        <i class="bi bi-search opacity-50" id="mobile-search-icon"></i>
                    </div>

                    <div class="d-flex align-items-center pe-md-5 me-md-4">
                        <label class="theme-toggle d-flex align-items-center gap-2">
                            <i class="bi bi-sun-fill theme-icon-sun"></i>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="theme-toggle">
                            </div>
                            <i class="bi bi-moon-fill theme-icon-moon"></i>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Мобильная строка поиска -->
    <div class="mobile-search-bar d-md-none" style="display: none;">
        <div class="container-xl py-3">
            <form class="d-flex gap-2" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <input class="form-control"
                       type="search"
                       placeholder="Поищем?"
                       aria-label="Search"
                       name="s"
                       value="<?php echo get_search_query(); ?>">
                <button class="btn btn-primary" type="submit">Найти</button>
            </form>
        </div>
    </div>
</header>

<!-- Контейнер для основного контента -->
<main class="container-xl px-0 px-md-4">

