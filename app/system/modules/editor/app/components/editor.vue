<template>
    <div :class="['pk-editor', editorMode, {'uk-invisible': !ready && !editorMode}]">
        <template v-if="editorMode === 'split'">
            <ul ref="tab" class="uk-subnav uk-flex-right" uk-switcher>
                <li><a href="">{{ 'Visual' | trans }}</a></li>
                <li><a href="">{{ 'Code' | trans }}</a></li>
            </ul>
            <ul class="uk-switcher" :class="{'uk-invisible': !ready}">
                <li ref="visual">
                    <textarea ref="editor" v-model="content" autocomplete="off" :style="{height: height + 'px'}" :class="{'uk-invisible': !show}" />
                </li>
                <li ref="code">
                    <textarea ref="editor-code" v-model="content" autocomplete="off" :style="{height: height + 'px'}" :class="{'uk-invisible': !show}" />
                </li>
            </ul>
        </template>
        <template v-else>
            <textarea ref="editor" v-model="content" autocomplete="off" :style="{height: height + 'px'}" :class="{'uk-invisible': !show}" />
        </template>
    </div>
</template>

<script>

import { $, on, addClass, closest } from 'uikit-util';

// Utils
import ImagePicker from './image-picker.vue';
import VideoPicker from './video-picker.vue';
import LinkPicker from './link-picker.vue';

// Codemirror
import EditorCode from './editor-code';

// HTMLEditor
import EditorHtml from './htmleditor/editor-html';
import EditorHtmlPluginLink from './htmleditor/link';
import EditorHtmlPluginImage from './htmleditor/image';
import EditorHtmlPluginVideo from './htmleditor/video';
import EditorHtmlPluginUrl from './htmleditor/url';

// TinyMCE
import TinyMCE from './tinymce/editor-tinymce';
import TinyMCEPluginLink from './tinymce/link';
import TinyMCEPluginImage from './tinymce/image';
import TinyMCEPluginVideo from './tinymce/video';

const VEditor = {

    components: {

        'editor-textarea': { // eslint-disable-line vue/no-unused-components

            created() {
                this.$emit('ready');
                this.$set(this.$parent, 'show', true);
            }

        },

        'editor-code': EditorCode // eslint-disable-line vue/no-unused-components

    },

    props: ['type', 'mode', 'value', 'options'],

    data() {
        return {
            editor: {},
            height: 500,
            show: false,
            active: 0,
            ready: false,
            // TODO
            content: this.value
        };
    },

    // TODO
    editors: {
        html: {
            'editor-html': EditorHtml,
            'plugin-link': EditorHtmlPluginLink,
            'plugin-image': EditorHtmlPluginImage,
            'plugin-video': EditorHtmlPluginVideo,
            'plugin-url': EditorHtmlPluginUrl
        },
        tinymce: {
            'editor-html': TinyMCE,
            'plugin-link': TinyMCEPluginLink,
            'plugin-image': TinyMCEPluginImage,
            'plugin-video': TinyMCEPluginVideo
        },
        code: { 'editor-html': EditorCode }
    },

    unsplit: ['html', 'code', 'textarea'],

    computed: {
        editorType() {
            return this.type || window.$pagekit.editor.editor || 'textarea';
        },

        editorMode() {
            const editors = this.$options.unsplit;
            return (editors.indexOf(this.editorType) !== -1) ? '' : this.mode || window.$pagekit.editor.mode || '';
        }
    },

    watch: {
        value(content) {
            this.$set(this, 'content', content);
        },

        content(content) {
            this.$emit('input', content);
            this.$emit('editor-update', content);
        }
    },

    created() {
        this.createEditor();
        this.$on('hook:mounted', this.init);
    },

    mounted() {
        const vm = this;

        if (this.editorMode === 'split') {
            this.tab = UIkit.switcher(this.$refs.tab);

            on(this.tab.connects, 'show', (e, tab) => {
                if (tab !== vm.tab) return false;
                for (let i = 0; i < Object.keys(tab.toggles).length; i++) {
                    const index = Object.keys(tab.toggles)[i];
                    if (closest($(tab.toggles[index]), 'li').classList.contains('uk-active')) {
                        vm.active = index;
                        break;
                    }
                }
            });

            this.tab.show(this.active);
        }
    },

    methods: {

        createEditor() {
            const editors = Object.keys(this.$options.editors);

            if (editors.indexOf(this.editorType) !== -1) {
                _.extend(this.$options.components, this.$options.editors[this.editorType]);
            }
        },

        init() {
            if (this.editorMode === 'split') {
                const el = this.$el.previousElementSibling || this.$el.parentNode.previousElementSibling;
                el && addClass(el, 'uk-position-absolute');
            }

            if (this.options && this.options.height) {
                this.height = this.options.height;
            }

            if (this.$el.hasAttributes()) {
                const attrs = this.$el.attributes;

                for (let i = attrs.length - 1; i >= 0; i--) {
                    if (attrs[i].name !== 'class') {
                        this.$refs.editor.setAttribute(attrs[i].name, attrs[i].value);
                        this.$el.removeAttribute(attrs[i].name);
                    }
                }
            }

            const components = this.$options.components; const type = `editor-${this.type}`; const self = this;
            const EditorComponent = components[type] || components['editor-html'] || components['editor-textarea'];

            const Editor = Vue.extend(EditorComponent);

            new Editor({ parent: this }).$on('ready', function () {
                _.forIn(self.$options.components, (Component) => {
                    if (Component.plugin) {
                        const Plugin = Vue.extend(Component);
                        new Plugin({ parent: self });
                    }
                }, this);

                if (self.editorMode === 'split') {
                    self.addCode();
                }
            });
        },

        addCode() {
            const CodeEditor = Vue.extend(this.$options.components['editor-code']);
            new CodeEditor({ parent: this });
        }
    },

    utils: {
        'image-picker': Vue.extend(ImagePicker),
        'video-picker': Vue.extend(VideoPicker),
        'link-picker': Vue.extend(LinkPicker)
    }
};

Vue.component('VEditor', (resolve) => {
    resolve(VEditor);
});

export default VEditor;

</script>
