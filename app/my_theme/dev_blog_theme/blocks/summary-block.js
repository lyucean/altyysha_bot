( function( blocks, element, blockEditor ) {
    // Создаем локальные ссылки на часто используемые функции
    var el = element.createElement;
    var RichText = blockEditor.RichText;

    // Регистрируем новый блок
    blocks.registerBlockType( 'dev-blog-theme/summary', {
        // Название блока, которое будет отображаться в интерфейсе
        title: 'Саммари статьи',
        // Иконка блока
        icon: 'editor-alignleft',
        // Категория блока в меню добавления блоков
        category: 'common',
        // Определяем атрибуты блока
        attributes: {
            content: {
                type: 'array',
                source: 'children',
                selector: 'p'
            }
        },
        // Функция, определяющая, как блок выглядит в редакторе
        edit: function( props ) {
            var content = props.attributes.content;
            // Функция для обновления контента
            function onChangeContent( newContent ) {
                props.setAttributes( { content: newContent } );
            }
            // Возвращаем структуру блока для редактора
            return el(
                'div',
                { className: props.className + ' summary' },
                el( RichText, {
                    tagName: 'p',
                    onChange: onChangeContent,
                    value: content,
                    placeholder: 'Введите краткое описание статьи...'
                } )
            );
        },
        // Функция, определяющая, как блок сохраняется и отображается на фронтенде
        save: function( props ) {
            return el(
                'div',
                { className: 'summary' },
                el( RichText.Content, {
                    tagName: 'p',
                    value: props.attributes.content
                } )
            );
        },
    } );
// Передаем необходимые объекты WordPress в нашу функцию
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor );
