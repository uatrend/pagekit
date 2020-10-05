/**
 * Editor Image plugin.
 */

export default {

    name: 'plugin-image',

    plugin: true,

    created() {
        if (typeof tinyMCE === 'undefined') {
            return;
        }

        const vm = this;
        let image;

        this.$parent.editor.plugins.push('-pagekitImage');
        tinyMCE.PluginManager.add('pagekitImage', (editor) => {
            const showDialog = function () {
                let element = editor.selection.getNode();

                if (element.nodeName === 'IMG' && !element.hasAttribute('data-mce-object')) {
                    editor.selection.select(element);
                    image = { src: element.attributes.src.nodeValue, alt: element.attributes.alt.nodeValue };
                } else {
                    element = new Image() || document.createElement('img');
                    image = {};
                }

                const Picker = Vue.extend(vm.$parent.$options.utils['image-picker']);

                new Picker({
                    name: 'image-picker',
                    parent: vm,
                    data: { image: { data: image } }
                }).$mount()
                    .$on('select', (img) => {
                        element.setAttribute('src', '');
                        element.setAttribute('alt', '');

                        const attributes = Object.keys(element.attributes).reduce((previous, key) => {
                            const name = element.attributes[key].name;

                            if (name === 'data-mce-src') {
                                return previous;
                            }

                            return `${previous} ${name}="${img.data[name] || element.attributes[key].nodeValue}"`;
                        }, '');

                        editor.selection.setContent(
                            `<img${attributes}>`
                        );

                        editor.fire('change');
                    });
            };

            editor.ui.registry.addToggleButton('image', {
                tooltip: 'Insert/edit image',
                icon: 'image',
                onAction() {
                    showDialog();
                },
                onSetup(api) {
                    return editor.selection.selectorChangedWithUnbind('img:not([data-mce-object],[data-mce-placeholder]),figure.image', (state) => {
                        api.setActive(state);
                        if (state) showDialog();
                    }).unbind;
                }
            });

            editor.ui.registry.addMenuItem('image', {
                icon: 'image',
                text: 'Insert/edit image',
                context: 'insert',
                onAction() {
                    showDialog();
                }
            });
        });
    }

};
