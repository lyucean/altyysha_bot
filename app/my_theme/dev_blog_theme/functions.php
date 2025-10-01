<?php
// Основные функции темы для сайта-поздравления

// Подключаем стили и скрипты
function birthday_theme_enqueue_styles() {
    // Bootstrap
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array(), null, true);

    // Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;700&family=Inter:wght@300;400;500;600;700&display=swap');

    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

    // Стили темы
    $style_path = get_stylesheet_directory() . '/style.css';
    $version = date('Ymd.His', filemtime($style_path));
    wp_enqueue_style('birthday-theme-style', get_stylesheet_uri(), array(), $version);
}
add_action('wp_enqueue_scripts', 'birthday_theme_enqueue_styles');

function theme_enqueue_scripts() {
    // Подключаем скрипт для мобильной строки поиска
//    wp_enqueue_script('mobile-search', get_template_directory_uri() . '/js/mobile-search.js', array(), '1.0.0', true);
    // Подключаем скрипт промотки страницы вверх
//    wp_enqueue_script('scroll-top', get_template_directory_uri() . '/js/scroll-top.js', array(), '1.0.0', true);
//     общий скрипт
    wp_enqueue_script('animation', get_template_directory_uri() . '/js/animation.js', array(), '1.1.0', true);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');// Поддержка функций темы

function birthday_theme_setup() {
    // Поддержка заголовков
    add_theme_support('title-tag');

    // Поддержка миниатюр
    add_theme_support('post-thumbnails');

    // Поддержка меню
    add_theme_support('menus');

    // Регистрируем меню
    register_nav_menus(array(
        'primary' => 'Основное меню',
    ));

    // Поддержка HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'birthday_theme_setup');

// Настройки для постов-поздравлений
function birthday_posts_init() {
    // Можно добавить кастомные поля для поздравлений
    add_post_type_support('post', 'excerpt');
}
add_action('init', 'birthday_posts_init');

// Убираем лишние элементы из head
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');

// Автоматическая настройка главной страницы
function setup_birthday_pages() {
    // Создаем главную страницу если её нет
    $home_page = get_page_by_title('Главная');
    if (!$home_page) {
        $home_page_id = wp_insert_post(array(
            'post_title' => 'Главная',
            'post_content' => 'Главная страница сайта-поздравления',
            'post_status' => 'publish',
            'post_type' => 'page'
        ));
    } else {
        $home_page_id = $home_page->ID;
    }

    // Создаем страницу для постов
    $blog_page = get_page_by_title('Поздравления');
    if (!$blog_page) {
        $blog_page_id = wp_insert_post(array(
            'post_title' => 'Поздравления',
            'post_content' => 'Все поздравления от друзей',
            'post_status' => 'publish',
            'post_type' => 'page'
        ));
    } else {
        $blog_page_id = $blog_page->ID;
    }

    // Устанавливаем настройки чтения
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_page_id);
    update_option('page_for_posts', $blog_page_id);
}

// Запускаем при активации темы
add_action('after_switch_theme', 'setup_birthday_pages');


?>

