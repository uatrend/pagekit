/**
 * Editor Video plugin.
 */

import { on, attr, findAll } from 'uikit-util';
import VideoPreview from './video-preview.vue';

export default {

    name: 'video-plugin',

    data: () => ({ videos: [] }),

    plugin: true,

    created() {
        const vm = this;
        const editor = this.$parent.editor;

        if (!editor || !editor.htmleditor) {
            return;
        }

        editor.addButton('video', {
            title: 'Video',
            label: '<i uk-icon="video"></i>'
        });

        editor.addToolbarButton('video');

        on(editor.$el, 'action.video', (e, edtr) => {
            e.stopImmediatePropagation();
            vm.openModal(_.find(vm.videos, (vid) => vid.inRange(edtr.getCursor())));
        });

        on(editor.$el, 'render', () => {
            vm.videos = editor.replaceInPreview(/<(video|iframe)([^>]*)>[^<]*<\/(?:video|iframe)>|\(video\)(\{.+?})/gi, vm.replaceInPreview);
        });

        on(editor.$el, 'renderLate', () => {
            while (vm.$children.length) {
                vm.$children[0].$destroy();
            }

            Vue.nextTick(() => {
                findAll('video-preview', editor.preview).forEach((el) => {
                    const Wrapper = vm.getWrapper(attr(el, 'index'));
                    const Component = new Wrapper();
                    Component.$mount(el);
                });
            });
        });

        editor.debouncedRedraw();
    },

    methods: {

        getWrapper(index) {
            return Vue.extend({
                name: 'Wrapper',
                parent: this,
                components: this.$options.components,
                data: () => this.$data,
                methods: { openModal(video) { return this.$parent.openModal(video); } },
                render: (h) => h('video-preview', { props: { index } })
            });
        },

        openModal(video) {
            const parser = new DOMParser();
            const editor = this.$parent.editor;
            const cursor = editor.editor.getCursor();

            if (!video) {
                video = {
                    replace(value) {
                        editor.editor.replaceRange(value, cursor);
                    }
                };
            }

            const picker = new this.$parent.$options.utils['video-picker']({
                parent: this,
                data: { video }
            }).$mount();

            picker.$on('select', (v) => {
                let attributes;
                let src;
                let match;

                delete v.data.playlist;

                if (match = picker.isYoutube) { // eslint-disable-line no-cond-assign
                    src = `https://www.youtube.com/embed/${match[1]}?`;

                    if (v.data.loop) {
                        v.data.playlist = match[1];
                    }
                } else if (match = picker.isVimeo) { // eslint-disable-line no-cond-assign
                    src = `https://player.vimeo.com/video/${match[3]}?`;
                }

                if (src) {
                    if (!v.anchor) {
                        v.anchor = parser.parseFromString('<iframe></iframe>', 'text/html').body.childNodes[0];
                    }

                    _.forEach(v.data, (value, key) => {
                        if (key === 'src' || key === 'width' || key === 'height') {
                            return;
                        }

                        src = `${src}${key}=${_.isBoolean(value) ? Number(value) : value}&`;
                    });

                    v.attributes = v.attributes || {};

                    v.attributes.src = src.slice(0, -1);
                    v.attributes.width = v.data.width || 690;
                    v.attributes.height = v.data.height || 390;
                    v.attributes.allowfullscreen = true;

                    attributes = v.attributes;
                } else {
                    if (!v.anchor) {
                        v.anchor = parser.parseFromString('<video></video>', 'text/html').body.childNodes[0];
                    }

                    attributes = v.data;
                }

                _.forEach(attributes, (value, key) => {
                    if (value) {
                        v.anchor.setAttribute(key, _.isBoolean(value) ? '' : value);
                    } else {
                        v.anchor.removeAttribute(key);
                    }
                });

                v.replace(v.anchor.outerHTML);
            });
        },

        replaceInPreview(data, index) {
            const parser = new DOMParser();
            let settings;
            let src;
            let query;

            if (!data.matches[3]) {
                data.data = {};
                data.anchor = parser.parseFromString(data.matches[0], 'text/html').body.childNodes[0];

                if (data.anchor.nodeName === 'VIDEO') {
                    _.forEach(data.anchor.attributes, (attribute) => {
                        data.data[attribute.name] = attribute.nodeValue === '' || attribute.nodeValue;
                    });

                    data.data.controls = data.data.controls !== undefined;
                } else if (data.anchor.nodeName === 'IFRAME') {
                    data.attributes = {};
                    _.forEach(data.anchor.attributes, (attribute) => {
                        data.attributes[attribute.name] = attribute.nodeValue === '' || attribute.nodeValue;
                    });

                    src = data.attributes.src || '';
                    src = src.split('?');
                    query = src[1] || '';
                    src = src[0];
                    query.split('&').forEach((param) => {
                        param = param.split('=');
                        data.data[param[0]] = param[1];
                    });

                    data.data.src = src;
                    if (data.attributes.width) {
                        data.data.width = data.attributes.width;
                    }
                    if (data.attributes.height) {
                        data.data.height = data.attributes.height;
                    }
                }
            } else {
                try {
                    settings = JSON.parse(data.matches[3]);
                } catch (e) {
                    console.error(e);
                }

                data.data = settings || { src: '' };
            }

            return `<video-preview index="${index}"></video-preview>`;
        }

    },

    components: { 'video-preview': VideoPreview }

};
