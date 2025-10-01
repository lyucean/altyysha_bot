(function(blocks, element) {
    var el = element.createElement;

    blocks.registerBlockType('dev-blog-theme/article-divider', {
        title: 'Разделитель статьи',
        icon: 'editor-insertmore',
        category: 'layout',
        description: 'Разделяет статью на две части, закрывая один блок article и открывая новый.',
        
        edit: function() {
            // Как блок выглядит в редакторе
            return el(
                'div', 
                { 
                    className: 'article-divider-block',
                    style: {
                        backgroundColor: '#f8f9fa',
                        padding: '20px',
                        margin: '20px 0',
                        borderRadius: '5px',
                        borderTop: '2px dashed #dee2e6',
                        borderBottom: '2px dashed #dee2e6',
                        textAlign: 'center'
                    }
                },
                el(
                    'div',
                    {
                        style: {
                            fontSize: '24px',
                            color: '#6c757d'
                        }
                    },
                    '✂️ Разделитель статьи'
                ),
                el(
                    'p',
                    {
                        style: {
                            fontSize: '14px',
                            color: '#6c757d'
                        }
                    },
                    'В этом месте статья будет разделена на две части'
                )
            );
        },
        
        save: function() {
            // Ничего не сохраняем в контенте, так как используем render_callback на серверной стороне
            return null;
        }
    });
}(
    window.wp.blocks,
    window.wp.element
));