/**
 * Editor Video plugin.
 */

export default {

    name: 'plugin-video',

    plugin: true,

    created() {
        if (typeof tinyMCE === 'undefined') {
            return;
        }

        const vm = this;

        this.$parent.editor.plugins.push('media');
        this.$parent.editor.plugins.push('-pagekitVideo');
        tinyMCE.PluginManager.add('pagekitVideo', (editor) => {
            const showDialog = function () {
                let query;
                let src;
                const attributes = {};
                let element = editor.selection.getNode();
                const video = {};

                if (element.nodeName === 'IMG' && element.hasAttribute('data-mce-object')) {
                    editor.selection.select(element);

                    Object.keys(element.attributes).forEach((key) => {
                        let name = element.attributes[key].name;

                        if (name === 'width' || name === 'height' || ((name = name.match(/data-mce-p-(.*)/)) && (name = name[1]))) { // eslint-disable-line no-cond-assign
                            video[name] = element.attributes[key].nodeValue === '' || element.attributes[key].nodeValue;
                        }
                    });
                } else if (element.nodeName === 'SPAN' && element.hasAttribute('data-mce-object') && (element = element.firstChild)) { // eslint-disable-line no-cond-assign
                    src = element.getAttribute('src');
                    src = src.split('?');
                    query = src[1];
                    src = src[0];
                    String(query).split('&').forEach((param) => {
                        param = param.split('=');
                        video[param[0]] = param[1];
                    });

                    video.src = src;
                    video.width = element.getAttribute('width');
                    video.height = element.getAttribute('height');

                    Object.keys(element.attributes).forEach((key) => {
                        const name = element.attributes[key].name;

                        if (name !== 'src' && name !== 'width' && name !== 'height') {
                            attributes[name] = element.attributes[key].nodeValue;
                        }
                    });
                }

                const PickerComponent = Vue.extend(vm.$parent.$options.utils['video-picker']);

                const picker = new PickerComponent({
                    parent: vm,
                    data() {
                        return { video: { data: video } };
                    }
                }).$mount().$on('select', (v) => {
                    let content;
                    let source;
                    let match;

                    delete v.data.playlist;

                    if (match = picker.isYoutube) { // eslint-disable-line no-cond-assign
                        source = `https://www.youtube.com/embed/${match[1]}?`;

                        if (v.data.loop) {
                            v.data.playlist = match[1];
                        }
                    } else if (match = picker.isVimeo) { // eslint-disable-line no-cond-assign
                        source = `https://player.vimeo.com/video/${match[3]}?`;
                    }

                    if (source) {
                        Object.keys(v.data).forEach((attr) => {
                            if (attr === 'src' || attr === 'width' || attr === 'height') {
                                return;
                            }

                            source = `${source}${attr}=${_.isBoolean(v.data[attr]) ? Number(v.data[attr]) : v.data[attr]}&`;
                        });

                        attributes.src = source.slice(0, -1);
                        attributes.width = v.data.width || 690;
                        attributes.height = v.data.height || 390;
                        attributes.allowfullscreen = true;

                        content = '<iframe';
                        Object.keys(attributes).forEach((attr) => {
                            content = `${content} ${attr}${_.isBoolean(attributes[attr]) ? '' : `="${attributes[attr]}"`}`;
                        });

                        content = `${content}></iframe>`;
                    } else {
                        content = '<video';

                        Object.keys(v.data).forEach((attr) => {
                            const value = v.data[attr];
                            if (value) {
                                content = `${content} ${attr}${_.isBoolean(value) ? '' : `="${value}"`}`;
                            }
                        });

                        content = `${content}></video>`;
                    }

                    editor.selection.setContent('');
                    editor.insertContent(content);

                    editor.fire('change');
                });
            };

            editor.ui.registry.addIcon('media', '<svg width="24" height="24"><path d="M4 3h16c.6 0 1 .4 1 1v16c0 .6-.4 1-1 1H4a1 1 0 0 1-1-1V4c0-.6.4-1 1-1zm1 2v14h14V5H5zm4.8 2.6l5.6 4a.5.5 0 0 1 0 .8l-5.6 4A.5.5 0 0 1 9 16V8a.5.5 0 0 1 .8-.4z" fill-rule="nonzero"></path></svg>');

            editor.ui.registry.addToggleButton('media', {
                tooltip: 'Insert/edit video',
                icon: 'media',
                onAction() {
                    showDialog();
                },
                onSetup(api) {
                    return editor.selection.selectorChangedWithUnbind('img[data-mce-object], span[data-mce-object]', (state) => {
                        api.setActive(state);
                        if (state) showDialog();
                    }).unbind;
                }
            });

            editor.ui.registry.addMenuItem('media', {
                icon: 'media',
                text: 'Insert/edit video',
                context: 'insert',
                onAction() {
                    showDialog();
                }
            });
        });
    }

};
