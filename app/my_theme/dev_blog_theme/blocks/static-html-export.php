<?php
/**
 * Класс для создания статичного HTML
 * Простая реализация без внешних зависимостей
 */
class HTML_Content_Cleaner {
    private $html;

    public function __construct($html = '') {
        $this->html = $html;
    }

    public function convert() {
        $html = $this->html;

        // Предварительная обработка HTML
        $html = $this->preprocess($html);

        // Заголовки (сохраняем с переносами строк)
        $html = preg_replace('/<h1[^>]*>(.*?)<\/h1>/is', "<h1>$1</h1>\n\n", $html);
        $html = preg_replace('/<h2[^>]*>(.*?)<\/h2>/is', "<h2>$1</h2>\n\n", $html);
        $html = preg_replace('/<h3[^>]*>(.*?)<\/h3>/is', "<h3>$1</h3>\n\n", $html);
        $html = preg_replace('/<h4[^>]*>(.*?)<\/h4>/is', "<h4>$1</h4>\n\n", $html);
        $html = preg_replace('/<h5[^>]*>(.*?)<\/h5>/is', "<h5>$1</h5>\n\n", $html);
        $html = preg_replace('/<h6[^>]*>(.*?)<\/h6>/is', "<h6>$1</h6>\n\n", $html);

        // Параграфы (с переносами)
        $html = preg_replace('/<p[^>]*>(.*?)<\/p>/is', "<p>$1</p>\n\n", $html);

        // Списки (с отступами и переносами)
        $html = preg_replace_callback('/<ul[^>]*>(.*?)<\/ul>/is', function($matches) {
            $items = preg_replace('/<li[^>]*>(.*?)<\/li>/is', "<li>$1</li>\n", $matches[1]);
            return "<ul>\n" . $items . "</ul>\n\n";
        }, $html);

        $html = preg_replace_callback('/<ol[^>]*>(.*?)<\/ol>/is', function($matches) {
            $items = preg_replace('/<li[^>]*>(.*?)<\/li>/is', "<li>$1</li>\n", $matches[1]);
            return "<ol>\n" . $items . "</ol>\n\n";
        }, $html);

        // Цитаты (с переносами)
        $html = preg_replace('/<blockquote[^>]*>(.*?)<\/blockquote>/is', "<blockquote>$1</blockquote>\n\n", $html);

        // Таблицы (с базовой структурой)
        $html = preg_replace_callback('/<table[^>]*>(.*?)<\/table>/is', function($matches) {
            return "<table>\n" . $matches[1] . "</table>\n\n";
        }, $html);

        // Изображения (сохраняем src и alt атрибуты)
        $html = preg_replace('/<img[^>]*src=["\'](.*?)["\'][^>]*alt=["\'](.*?)["\'][^>]*>/is', "<img src=\"$1\" alt=\"$2\">\n\n", $html);
        $html = preg_replace('/<img[^>]*alt=["\'](.*?)["\'][^>]*src=["\'](.*?)["\'][^>]*>/is', "<img src=\"$2\" alt=\"$1\">\n\n", $html);
        $html = preg_replace('/<img[^>]*src=["\'](.*?)["\'][^>]*>/is', "<img src=\"$1\">\n\n", $html);

        // Горизонтальные линии
        $html = preg_replace('/<hr[^>]*>/i', "<hr>\n\n", $html);

        // Очищаем от лишних атрибутов для всех тегов кроме img
        $html = preg_replace('/<(?!img)([a-z][a-z0-9]*)[^>]*>/i', '<$1>', $html);

        // Постобработка
        $html = $this->postprocess($html);

        return $html;
    }

    private function preprocess($html) {
        // Удаляем комментарии
        $html = preg_replace('/<!--.*?-->/s', '', $html);

        // Удаляем скрипты и стили
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);

        // Удаляем все div'ы, сохраняя содержимое
        $html = preg_replace('/<div[^>]*>/i', '', $html);
        $html = preg_replace('/<\/div>/i', '', $html);

        return $html;
    }

    private function postprocess($html) {
        // Удаляем пустые теги
        $html = preg_replace('/<([a-z][a-z0-9]*)[^>]*>\s*<\/\1>/i', '', $html);

        // Удаляем множественные переносы строк
        $html = preg_replace('/\n{3,}/', "\n\n", $html);

        // Удаляем пробелы в начале и конце строк
        $html = preg_replace('/^\s+|\s+$/m', '', $html);

        return $html;
    }
}

/**
 * Функция для создания чистого HTML
 */
function clean_html_content($html) {
    $converter = new HTML_Content_Cleaner($html);
    return $converter->convert();
}

/**
 * Обработчик запроса на скачивание HTML
 */
function process_html_download_request() {
    if (isset($_GET['download_html'])) {
        $post_id = intval($_GET['download_html']);

        // Получаем пост
        $post = get_post($post_id);
        if (!$post) {
            wp_die('Пост не найден');
        }

        // Получаем содержимое поста
        $content = $post->post_content;

        // Конвертируем в чистый HTML
        $clean_html = clean_html_content($content);

        // Формируем базовую структуру HTML-документа
        $html = "<html>\n<body>\n";
        $html .= "<h1>" . esc_html($post->post_title) . "</h1>\n\n";
        $html .= $clean_html;
        $html .= "</body>\n</html>";

        // Формируем имя файла из ярлыка URL
        $filename = $post->post_name . '.html';
        if (empty($post->post_name)) {
            $filename = sanitize_title($post->post_title) . '.html';
        }

        // Отправляем заголовки для скачивания
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($html));

        // Выводим содержимое и завершаем выполнение
        echo $html;
        exit;
    }
}
add_action('template_redirect', 'process_html_download_request');

/**
 * Добавляем страницу в админку для экспорта статей в HTML
 */
function add_html_export_page() {
    add_submenu_page(
        'tools.php',           // родительская страница (Инструменты)
        'Экспорт в HTML',      // заголовок страницы
        'Экспорт в HTML',      // название в меню
        'manage_options',      // необходимые права
        'html-export',         // slug страницы
        'html_export_page_content' // функция для отображения
    );
}
add_action('admin_menu', 'add_html_export_page');

/**
 * Содержимое страницы экспорта в HTML
 */
function html_export_page_content() {
    ?>
    <div class="wrap">
        <h1>Экспорт статей в HTML</h1>

        <p>Выберите статьи, которые вы хотите экспортировать в формате HTML.</p>

        <table class="wp-list-table widefat fixed striped posts">
            <thead>
            <tr>
                <th scope="col" class="manage-column column-title column-primary">
                    Заголовок
                </th>
                <th scope="col" class="manage-column">
                    Ярлык URL
                </th>
                <th scope="col" class="manage-column">
                    Автор
                </th>
                <th scope="col" class="manage-column">
                    Дата
                </th>
                <th scope="col" class="manage-column">
                    Действия
                </th>
            </tr>
            </thead>
            <tbody id="the-list">
            <?php
            $posts = get_posts([
                'post_type' => ['post', 'page'],
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC'
            ]);

            foreach ($posts as $post) {
                $author = get_the_author_meta('display_name', $post->post_author);
                ?>
                <tr>
                    <td class="title column-title has-row-actions column-primary">
                        <strong><?php echo esc_html($post->post_title); ?></strong>
                    </td>
                    <td><?php echo esc_html($post->post_name); ?></td>
                    <td><?php echo esc_html($author); ?></td>
                    <td><?php echo get_the_date('Y-m-d', $post->ID); ?></td>
                    <td>
                        <a href="<?php echo esc_url(add_query_arg('download_html', $post->ID, get_permalink($post->ID))); ?>" class="button">
                            Скачать как HTML
                        </a>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php
}