<?php
function register_summary_block() {
    // Регистрируем скрипт для блока
    wp_register_script(
        'summary-block',
        get_template_directory_uri() . '/blocks/summary-block.js',
        array( 'wp-blocks', 'wp-element', 'wp-editor' ),
        filemtime( get_template_directory() . '/blocks/summary-block.js' )
    );

    // Регистрируем стили для фронтенда
    wp_register_style(
        'summary-block-front',
        get_template_directory_uri() . '/blocks/summary-block.css',
        array(),
        filemtime(get_template_directory() . '/blocks/summary-block.css')
    );

    // Регистрируем стили для редактора (используем те же стили, что и для фронтенда)
    wp_register_style(
        'summary-block-editor',
        get_template_directory_uri() . '/blocks/summary-block.css',
        array(),
        filemtime(get_template_directory() . '/blocks/summary-block.css')
    );

    // Регистрируем блок
    register_block_type('dev-blog-theme/summary', array(
        'editor_script' => 'summary-block',
        'editor_style'  => 'summary-block-editor',
        'style'         => 'summary-block-front',
    ));
}
add_action( 'init', 'register_summary_block' );

?>
