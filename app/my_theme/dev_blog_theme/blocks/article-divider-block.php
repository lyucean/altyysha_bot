<?php
/**
 * Блок разделитель статьи
 * 
 * Этот блок позволяет разделить статью на две части,
 * закрывая один блок article и открывая новый.
 */

function register_article_divider_block() {
    // Регистрируем скрипты блока
    wp_register_script(
        'article-divider-block',
        get_template_directory_uri() . '/blocks/article-divider-block.js',
        array('wp-blocks', 'wp-element', 'wp-editor'),
        filemtime(get_template_directory() . '/blocks/article-divider-block.js')
    );

    // Регистрируем блок
    register_block_type('dev-blog-theme/article-divider', array(
        'editor_script' => 'article-divider-block',
        'render_callback' => 'render_article_divider_block'
    ));
}
add_action('init', 'register_article_divider_block');

/**
 * Функция рендеринга блока
 */
function render_article_divider_block($attributes, $content) {
    // Этот HTML будет вставлен в контент статьи
    return '</div></article><article class="rounded mb-4"><div class="article-content">';
}