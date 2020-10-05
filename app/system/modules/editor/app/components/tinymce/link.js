/**
 * Editor Link plugin.
 */

export default {

    name: 'plugin-link',

    plugin: true,

    created() {
        if (typeof tinyMCE === 'undefined') {
            return;
        }

        const vm = this;
        let link;

        this.$parent.editor.plugins.push('-pagekitLink');
        tinyMCE.PluginManager.add('pagekitLink', (editor) => {
            const showDialog = function () {
                let element = editor.selection.getNode();

                if (element.nodeName === 'A') {
                    editor.selection.select(element);
                    link = { link: element.attributes.href ? element.attributes.href.nodeValue : '', txt: element.innerHTML };
                } else {
                    element = document.createElement('a');
                    link = {};
                }

                const Picker = Vue.extend(vm.$parent.$options.utils['link-picker']);

                new Picker({
                    parent: vm,
                    data: { link }
                }).$mount()
                    .$on('select', (lnk) => {
                        element.setAttribute('href', '');

                        const attributes = Object.keys(element.attributes).reduce((previous, key) => {
                            const name = element.attributes[key].name;

                            if (name === 'data-mce-href') {
                                return previous;
                            }

                            return `${previous} ${name}="${name === 'href' ? lnk.link : element.attributes[key].nodeValue}"`;
                        }, '');

                        editor.selection.setContent(
                            `<a${attributes}>${lnk.txt}</a>`
                        );

                        editor.fire('change');
                    });
            // })
            };

            editor.on('click', (e) => {
                if (e.target.nodeName === 'A') {
                    showDialog();
                }
            });

            editor.ui.registry.addToggleButton('link', {
                tooltip: 'Insert/edit link',
                icon: 'link',
                onAction() {
                    showDialog();
                },
                onSetup(api) {
                    return editor.selection.selectorChangedWithUnbind('a', api.setActive).unbind;
                }
            });

            editor.ui.registry.addMenuItem('link', {
                context: 'insert',
                icon: 'link',
                text: 'Insert/edit link',
                onAction() {
                    showDialog();
                }
            });
        });
    }

};
