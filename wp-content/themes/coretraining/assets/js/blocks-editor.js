/**
 * CoreTraining Gutenberg blocks — editor
 */
(function (wp) {
    'use strict';

    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var Fragment = wp.element.Fragment;
    var useBlockProps = wp.blockEditor.useBlockProps;
    var RichText = wp.blockEditor.RichText;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;

    registerBlockType('coretraining/quote', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps({ className: 'ct-quote ct-quote--editor' });

            return el(
                Fragment,
                null,
                el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        { title: 'Citace' },
                        el(TextControl, {
                            label: 'Autor citace',
                            value: attributes.author,
                            onChange: function (value) {
                                setAttributes({ author: value });
                            },
                        })
                    )
                ),
                el(
                    'blockquote',
                    blockProps,
                    el(RichText, {
                        tagName: 'p',
                        placeholder: 'Text citace…',
                        value: attributes.content,
                        onChange: function (value) {
                            setAttributes({ content: value });
                        },
                    }),
                    attributes.author
                        ? el('cite', null, attributes.author)
                        : null
                )
            );
        },
        save: function () {
            return null;
        },
    });

    registerBlockType('coretraining/info-box', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps({
                className: 'ct-info-box ct-info-box--' + attributes.variant + ' ct-info-box--editor',
            });

            return el(
                Fragment,
                null,
                el(
                    InspectorControls,
                    null,
                    el(
                        PanelBody,
                        { title: 'Info box' },
                        el(SelectControl, {
                            label: 'Typ',
                            value: attributes.variant,
                            options: [
                                { label: 'Tip', value: 'tip' },
                                { label: 'Upozornění', value: 'warning' },
                                { label: 'Shrnutí', value: 'summary' },
                            ],
                            onChange: function (value) {
                                setAttributes({ variant: value });
                            },
                        })
                    )
                ),
                el(
                    'aside',
                    blockProps,
                    el(RichText, {
                        tagName: 'div',
                        multiline: 'p',
                        placeholder: 'Obsah boxu…',
                        value: attributes.content,
                        onChange: function (value) {
                            setAttributes({ content: value });
                        },
                    })
                )
            );
        },
        save: function () {
            return null;
        },
    });
})(window.wp);
