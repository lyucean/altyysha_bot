(function(wp) {
    var registerFormatType = wp.richText.registerFormatType;
    var toggleFormat = wp.richText.toggleFormat;
    var RichTextToolbarButton = wp.blockEditor.RichTextToolbarButton;
    var createElement = wp.element.createElement;

    registerFormatType('custom/spoiler-format', {
        title: 'Спойлер',
        tagName: 'span',
        className: 'spoiler-text',
        attributes: {
            'data-spoiler': 'data-spoiler'
        },
        edit: function(props) {
            return createElement(RichTextToolbarButton, {
                icon: 'visibility',
                title: 'Спойлер',
                onClick: function() {
                    props.onChange(toggleFormat(props.value, {
                        type: 'custom/spoiler-format',
                        attributes: {
                            'data-spoiler': 'true'
                        }
                    }));
                },
                isActive: props.isActive
            });
        }
    });
})(window.wp);
