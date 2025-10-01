<?php
// Используется для добавления функций и подключения стилей/скриптов.

// Подключаем стили и скрипты
function dev_blog_theme_enqueue_styles() {
    // Подключаем Bootstrap
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    // Bootstrap Icons
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');
    // Bootstrap JS
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), null, true);

    // Получаем путь к файлу style.css
    $style_path = get_stylesheet_directory() . '/style.css';
    // Получаем время последней модификации файла. Будет выглядеть как: 20241125.170600
    $version = date('Ymd.His', filemtime($style_path));

    // Подключаем стили темы с версией
    wp_enqueue_style('dev_blog_theme-style', get_stylesheet_uri(), array(), $version);
}
add_action('wp_enqueue_scripts', 'dev_blog_theme_enqueue_styles'); // Подключаем стили и скрипты когда WordPress загружает скрипты и стили

// Поддержка миниатюр. Нужен т.к. я планирую использовать миниатюры для постов/страниц
add_theme_support('post-thumbnails');

// Позволяет пользователям менять шапку сайта через админку
add_theme_support('custom-header');

// Подключаем скрипт для переключения тем
function enqueue_theme_scripts() {
    wp_enqueue_script('theme-toggle', get_template_directory_uri() . '/js/theme-toggle.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_theme_scripts');

function theme_enqueue_scripts() {
    // Подключаем скрипт для мобильной строки поиска
    wp_enqueue_script('mobile-search', get_template_directory_uri() . '/js/mobile-search.js', array(), '1.0.0', true);
    // Подключаем скрипт промотки страницы вверх
    wp_enqueue_script('scroll-top', get_template_directory_uri() . '/js/scroll-top.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');

// Регистрация меню. Нужно только если у вас будет навигационное меню, которое можно редактировать через админку
function register_theme_menus() {
    register_nav_menus(array(
        'sidebar-menu' => __('Боковое меню', 'dev_blog_theme'),
        'additional-menu' => __('Дополнительное меню', 'dev_blog_theme'),
        'block-menu' => __('Блок меню', 'dev_blog_theme'),
    ));
}
add_action('init', 'register_theme_menus');

// Bootstrap 5 Nav Walker для меню
class Bootstrap_5_Nav_Walker extends Walker_Nav_Menu {
    // Массив с иконками Bootstrap для каждого пункта меню
    private $menu_icons = [
        'Главная' => 'bi bi-house',
        'Менеджмент' => 'bi bi-graph-up-arrow',
        'DevOps' => 'bi bi-hdd-network',
        'Карьера' => 'bi bi-ladder',
        'Ответы' => 'bi bi-question-circle',
        'Личное' => 'bi bi-person',
        'Ресурсы' => 'bi bi-book',
        'Инструменты' => 'bi bi-tools',
        'Проекты' => 'bi bi-kanban',
        'Письма' => 'bi bi-bell',
        'Обо мне' => 'bi bi-person-vcard',
        'Процессы' => 'bi bi-diagram-3',
        'Разработка' => 'bi bi-code-square',
        'Мысли' => 'bi bi-lightbulb',
        'Практика' => 'bi bi-clipboard-check',
        'Менторинг' => 'bi bi-mortarboard',
        'Автор' => 'bi-pen',
        'Ищу работу' => 'bi bi-fire text-danger',
    ];

    function start_lvl(&$output, $depth = 0, $args = null): void
    {
        // $output - строка, куда записывается HTML-код
        // $depth - текущий уровень вложенности (0 - верхний уровень)
        // $args - аргументы, переданные в wp_nav_menu()

        $output .= "<ul class='nav flex-column'>"; // Открывающий тег для подменю
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0): void
    {
        // Получаем иконку для текущего пункта меню из массива
        // Если иконка не найдена, используем универсальную иконку 'bi bi-chevron-right'
        $icon_class = $this->menu_icons[$item->title] ?? 'bi bi-chevron-right';

        // Формируем HTML для пункта меню
        $output .= "<li class='nav-item'>";
        $output .= sprintf(
            "<a href='%s' class='nav-link d-flex align-items-center gap-2 py-2 px-3 rounded-3'>
            <i class='%s'></i>
            %s
        </a>",
            $item->url,
            $icon_class,
            $item->title
        );
    }
}


add_action('after_setup_theme', function() {
    // Добавляем поддержку thumbnails
    add_theme_support('post-thumbnails');

    // Добавляем свой размер изображения с высоким качеством
    add_image_size('article-thumb', 1200, 630, true);
});

// Добавляем счетчик просмотров
function set_post_views() {
    if (is_single()) {
        $post_id = get_the_ID();
        $count = get_post_meta($post_id, 'post_views_count', true);

        if ($count == '') {
            delete_post_meta($post_id, 'post_views_count');
            add_post_meta($post_id, 'post_views_count', 1);
        } else {
            update_post_meta($post_id, 'post_views_count', $count + 1);
        }
    }
}
add_action('wp_head', 'set_post_views');

// Функция для получения количества просмотров
function get_post_views($post_id) {
    $count_key = 'post_views_count';
    $count = get_post_meta($post_id, $count_key, true);

    if ($count == '') {
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
        return "0";
    }
    return $count;
}

// Функция для получения SVG-паттерна
function get_random_pattern() {
    return '<svg width="100%" height="100%" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <pattern id="tech-pattern" x="0" y="0" width="50" height="50" patternUnits="userSpaceOnUse">
                <!-- Сетка -->
                <path d="M 0 0 L 50 0 M 0 10 L 50 10 M 0 20 L 50 20 M 0 30 L 50 30 M 0 40 L 50 40 M 0 50 L 50 50" 
                      stroke="#6c757d" stroke-width="0.5" fill="none"/>
                <path d="M 0 0 L 0 50 M 10 0 L 10 50 M 20 0 L 20 50 M 30 0 L 10 50 M 20 0 L 20 50 M 30 0 L 30 50 M 40 0 L0 L 50 50" 
                      stroke="#6c757d" stroke-width="0.5" fill="none"/>
                
                <!-- IT символы -->
                <text x="5" y="15" font-family="monospace" font-size="8" fill="#dee2e6">no</text>
                <text x="20" y="15" font-family="monospace" font-size="8" fill="#dee2e6">image</text>
                <text x="15" y="35" font-family="monospace" font-size="4" fill="#dee2e6"> 10 50 M 20 0 L</text>
                <text x="5" y="45" font-family="monospace" font-size="4" fill="#dee2e6">M 20 0 L 20 50 M 30 0 L </text>
                
                <!-- Круги -->
                <circle cx="25" cy="25" r="20" stroke="#6c757d" stroke-width="0.5" fill="none"/>
                <circle cx="25" cy="25" r="15" stroke="#6c757d" stroke-width="0.5" fill="none"/>
                
                <!-- Линии -->
                <path d="M 0 0 L 50 50 M 50 0 L 0 50" stroke="#6c757d" stroke-width="0.5" fill="none"/>
            </pattern>
        </defs>
        <rect width="100%" height="100%" fill="#495057"/>
        <rect width="100%" height="100%" fill="url(#tech-pattern)"/>
    </svg>';
}

// Функция для расчета времени чтения
function get_reading_time($content) {
    // Очищаем текст от HTML тегов
    $content = strip_tags($content);

    // Удаляем пробелы в начале и конце
    $content = trim($content);

    // Заменяем множественные пробелы на один
    $content = preg_replace('/\s+/', ' ', $content);

    // Считаем количество слов, разделяя текст по пробелам
    $word_count = count(explode(' ', $content));

    // Подсчет времени на чтение текста
    // Средняя скорость чтения - 150-200 слов в минуту
    $reading_time = ceil($word_count / 150);

    // Подсчет количества изображений в контенте
    $image_count = substr_count(get_the_content(), '<img');

    // Добавляем 0.5 минуты за каждое изображение
    $image_time = $image_count * 0.5;

    // Округляем общее время до ближайшего целого числа
    return ceil($reading_time + $image_time);
}

// Добавляем счетчик уникальных просмотров по IP
function set_unique_post_views() {
    if (is_single()) {
        $post_id = get_the_ID();
        $ip_address = $_SERVER['REMOTE_ADDR'];

        // Получаем текущие уникальные просмотры
        $unique_views = get_post_meta($post_id, 'unique_post_views', true) ?: 0;

        // Получаем массив IP-адресов, которые уже просмотрели пост
        $viewed_ips = get_post_meta($post_id, 'viewed_ips', true);
        if (!is_array($viewed_ips)) {
            $viewed_ips = array();
        }

        // Проверяем, просматривал ли уже этот IP данный пост
        if (!in_array($ip_address, $viewed_ips)) {
            // Добавляем IP в список просмотревших
            $viewed_ips[] = $ip_address;
            update_post_meta($post_id, 'viewed_ips', $viewed_ips);

            // Увеличиваем счетчик уникальных просмотров
            update_post_meta($post_id, 'unique_post_views', $unique_views + 1);
        }
    }
}
add_action('wp_head', 'set_unique_post_views');

// Функция для получения количества уникальных просмотров
function get_unique_post_views($post_id) {
    $unique_views = get_post_meta($post_id, 'unique_post_views', true);
    return empty($unique_views) ? 0 : $unique_views;
}

// Опционально: функция для очистки старых IP-адресов (можно вызывать по крону)
function cleanup_viewed_ips() {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'fields' => 'ids'
    );

    $posts = get_posts($args);

    foreach ($posts as $post_id) {
        $viewed_ips = get_post_meta($post_id, 'viewed_ips', true);
        if (is_array($viewed_ips) && count($viewed_ips) > 1000) { // Ограничение на 1000 IP
            // Оставляем только последние 1000 IP
            $viewed_ips = array_slice($viewed_ips, -1000);
            update_post_meta($post_id, 'viewed_ips', $viewed_ips);
        }
    }
}

// Добавляем задачу в cron (выполняется раз в неделю)
if (!wp_next_scheduled('cleanup_viewed_ips_hook')) {
    wp_schedule_event(time(), 'weekly', 'cleanup_viewed_ips_hook');
}
add_action('cleanup_viewed_ips_hook', 'cleanup_viewed_ips');

// При деактивации плагина или темы
register_deactivation_hook(__FILE__, 'remove_cleanup_schedule');
function remove_cleanup_schedule() {
    wp_clear_scheduled_hook('cleanup_viewed_ips_hook');
}

// Подключение стилей для редактора Gutenberg
function add_custom_editor_styles() {
    // Регистрируем стили для редактора
    add_theme_support('editor-styles');

    // Подключаем стили
    add_editor_style([
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
        'editor-style.css'
    ]);
}
add_action('after_setup_theme', 'add_custom_editor_styles');

// Подключим наш блок с кратким описанием статьи
require_once get_template_directory() . '/blocks/summary-block.php';

// Подключим наш блок с конвертацией в Html
require_once get_template_directory() . '/blocks/static-html-export.php';

// Подключим наш блок с разрывом страницы
require_once get_template_directory() . '/blocks/article-divider-block.php';

// Подключим спойлер в выпадающее меню форматирования
require_once get_template_directory() . '/blocks/spoiler-block.php';