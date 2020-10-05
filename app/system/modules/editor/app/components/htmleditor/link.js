/**
 * Editor Link plugin.
 */

import { on, attr, findAll } from 'uikit-util';
import LinkPreview from './link-preview.vue';

export default {

    name: 'link-plugin',

    data: () => ({ links: [] }),

    plugin: true,

    created() {
        const vm = this;
        const editor = this.$parent.editor;

        if (!editor || !editor.htmleditor) {
            return;
        }

        on(editor.$el, 'action.link', (e, edtr) => {
            e.stopImmediatePropagation();
            vm.openModal(_.find(vm.links, (link) => link.inRange(edtr.getCursor())));
        });

        on(editor.$el, 'render', () => {
            const regexp = editor.getMode() !== 'gfm' ? /<a(?:\s.+?>|\s*>)(?:[^<]*)<\/a>/gi : /<a(?:\s.+?>|\s*>)(?:[^<]*)<\/a>|(?:\[([^\n\]]*)\])(?:\(([^\n\]]*)\))?/gi;
            vm.links = editor.replaceInPreview(regexp, vm.replaceInPreview);
        });

        on(editor.$el, 'renderLate', () => {
            while (vm.$children.length) {
                vm.$children[0].$destroy();
            }

            Vue.nextTick(() => {
                findAll('link-preview', editor.preview).forEach((el) => {
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
                methods: { openModal(link) { return this.$parent.openModal(link); } },
                render: (h) => h('link-preview', { props: { index } })
            });
        },

        openModal(link) {
            const parser = new DOMParser();
            const editor = this.$parent.editor;
            const cursor = editor.editor.getCursor();

            if (!link) {
                link = {
                    replace(value) {
                        editor.editor.replaceRange(value, cursor);
                    }
                };
            }

            const linkPicker = new this.$parent.$options.utils['link-picker']({
                parent: this,
                data: { link }
            }).$mount();

            linkPicker.$on('select', (lnk) => {
                if (!lnk.anchor) {
                    lnk.anchor = parser.parseFromString('<a></a>', 'text/html').body.childNodes[0];
                }

                lnk.anchor.setAttribute('href', lnk.link);
                lnk.anchor.innerHTML = lnk.txt;

                lnk.replace(lnk.anchor.outerHTML);
            });
        },

        replaceInPreview(data, index) {
            const parser = new DOMParser();

            data.data = {};
            if (data.matches[0][0] === '<') {
                data.anchor = parser.parseFromString(data.matches[0], 'text/html').body.childNodes[0];
                data.link = data.anchor.attributes.href ? data.anchor.attributes.href.nodeValue : '';
                data.txt = data.anchor.innerHTML;
            } else {
                if (data.matches[data.matches.length - 1][data.matches[data.matches.length - 2] - 1] === '!') return false;

                data.link = data.matches[2];
                data.txt = data.matches[1];
            }

            return `<link-preview index="${index}"></link-preview>`;
        }

    },

    components: { 'link-preview': LinkPreview }

};
