<!DOCTYPE html>
<html <?php language_attributes(); ?> lang="ru">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;700&amp;family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>

<!-- Прелоадер -->
<div class="nature-loader" id="loader">
    <div class="loader-leaf"></div>
</div>

<!-- Навигация -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#home">
            <i class="fas fa-leaf"></i>Дашенька
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#home">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">Обо мне</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#skills">Навыки</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#portfolio">Блог</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#hobbies">Увлечения</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Контакты</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
