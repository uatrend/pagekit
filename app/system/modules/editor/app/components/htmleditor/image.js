/**
 * Editor Image plugin.
 */

import { on, attr, findAll } from 'uikit-util';
import ImagePreview from './image-preview.vue';

export default {

    name: 'image-plugin',

    data: () => ({ images: [] }),

    plugin: true,

    created() {
        const vm = this;
        const editor = this.$parent.editor;

        if (!editor || !editor.htmleditor) {
            return;
        }

        // editor
        on(editor.$el, 'action.image', (e, edtr) => {
            e.stopImmediatePropagation();
            vm.openModal(_.find(vm.images, (img) => img.inRange(edtr.getCursor())));
        });

        on(editor.$el, 'render', () => {
            const regexp = editor.getMode() !== 'gfm' ? /<img(.+?)>/gi : /(?:<img(.+?)>|!(?:\[([^\n\]]*)])(?:\(([^\n\]]*?)\))?)/gi;
            vm.images = editor.replaceInPreview(regexp, vm.replaceInPreview);
        });

        on(editor.$el, 'renderLate', () => {
            while (vm.$children.length) {
                vm.$children[0].$destroy();
            }

            Vue.nextTick(() => {
                findAll('image-preview', editor.preview).forEach((el) => {
                    const Wrapper = vm.getWrapper(attr(el, 'index'));
                    const Component = new Wrapper();
                    Component.$mount(el);
                });
            });
        });
    },

    methods: {
        getWrapper(index) {
            return Vue.extend({
                name: 'Wrapper',
                parent: this,
                components: this.$options.components,
                data: () => this.$data,
                methods: { openModal(image) { return this.$parent.openModal(image); } },
                render: (h) => h('image-preview', { props: { index } })
            });
        },

        openModal(image) {
            const parser = new DOMParser();
            const editor = this.$parent.editor;
            const cursor = editor.editor.getCursor();

            if (!image) {
                image = {
                    replace(value) {
                        editor.editor.replaceRange(value, cursor);
                    }
                };
            }

            const imagePicker = new this.$parent.$options.utils['image-picker']({
                parent: this,
                data: { image }
            }).$mount();

            imagePicker.$on('select', (img) => {
                let content;

                if ((img.tag || editor.getCursorMode()) === 'html') {
                    if (!img.anchor) {
                        img.anchor = parser.parseFromString('<img>', 'text/html').body.childNodes[0];
                    }

                    img.anchor.setAttribute('src', img.data.src);
                    img.anchor.setAttribute('alt', img.data.alt);

                    content = img.anchor.outerHTML;
                } else {
                    content = `![${img.data.alt}](${img.data.src})`;
                }

                img.replace(content);
            });
        },

        replaceInPreview(data, index) {
            const parser = new DOMParser();

            data.data = {};
            if (data.matches[0][0] === '<') {
                data.anchor = parser.parseFromString(data.matches[0], 'text/html').body.childNodes[0];
                if (data.anchor.attributes['uk-img']) {
                    data.data['uk-img'] = data.anchor.attributes['uk-img'].nodeValue;
                }
                if (data.anchor.attributes['uk-svg']) {
                    data.data['uk-svg'] = data.anchor.attributes['uk-svg'].nodeValue;
                }
                if (data.anchor.attributes['uk-img'] || data.anchor.attributes['uk-svg']) {
                    data.data['data-src'] = data.anchor.attributes['data-src'].nodeValue;
                //     data.anchor.attributes.src = data.anchor.attributes['data-src'] ? data.anchor.attributes['data-src'] : data.anchor.attributes.src;
                }
                data.data.src = data.anchor.attributes.src ? data.anchor.attributes.src.nodeValue : '';
                data.data.alt = data.anchor.attributes.alt ? data.anchor.attributes.alt.nodeValue : '';
                data.tag = 'html';
            } else {
                data.data.src = data.matches[3];
                data.data.alt = data.matches[2];
                data.tag = 'gfm';
            }

            return `<image-preview index="${index}"></image-preview>`;
        }

    },

    components: { 'image-preview': ImagePreview }

};
