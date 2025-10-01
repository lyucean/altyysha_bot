<?php
function enqueue_spoiler_format() {
    wp_enqueue_script(
        'spoiler-format',
        get_template_directory_uri() . '/blocks/spoiler-block.js',
        array('wp-rich-text', 'wp-element', 'wp-block-editor'),
        filemtime(get_template_directory() . '/blocks/spoiler-block.js')
    );
}
add_action('enqueue_block_editor_assets', 'enqueue_spoiler_format');

// Стили для админки (редактор)
function spoiler_admin_styles() {
    wp_enqueue_style(
        'spoiler-admin-styles',
        get_template_directory_uri() . '/blocks/spoiler-admin-block.css',
        array(),
        filemtime(get_template_directory() . '/blocks/spoiler-admin-block.css')
    );
}
add_action('enqueue_block_editor_assets', 'spoiler_admin_styles');

// Стили для фронтенда
function spoiler_frontend_styles() {
    wp_enqueue_style(
        'spoiler-frontend-styles',
        get_template_directory_uri() . '/blocks/spoiler-block.css',
        array(),
        filemtime(get_template_directory() . '/blocks/spoiler-block.css')
    );
}
add_action('wp_enqueue_scripts', 'spoiler_frontend_styles');

// JavaScript для работы спойлеров на фронтенде
function spoiler_frontend_script() {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            const spoilers = document.querySelectorAll(".spoiler-text");
            
            spoilers.forEach(function(spoiler) {
                // Для мобильных - показывать по клику
                spoiler.addEventListener("click", function(e) {
                    e.preventDefault();
                    this.classList.add("revealed", "revealing");
                    
                    // Убираем класс анимации через 500мс
                    setTimeout(() => {
                        this.classList.remove("revealing");
                    }, 500);
                });
                
                // Для десктопа - скрывать обратно при уходе мыши
                spoiler.addEventListener("mouseleave", function() {
                    if (window.innerWidth > 768) {
                        this.classList.remove("revealed");
                    }
                });
            });
        });
    </script>';
}
add_action('wp_footer', 'spoiler_frontend_script');
