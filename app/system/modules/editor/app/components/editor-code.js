import { css, closest, removeAttr, height, trigger } from 'uikit-util';

export default {

    name: 'editor-code',

    created() {
        const baseURL = `${$editor.root_url}/app/assets/codemirror`;

        this.$asset({
            css: [
                `${baseURL}/show-hint.css`,
                `${baseURL}/codemirror.css`
            ],
            js: [
                `${baseURL}/codemirror.min.js`
            ]
        }).then(this.init);
    },

    methods: {
        init() {
            const self = this;
            const mode = this.$parent.editorMode;
            const $el = (mode !== 'split') ? this.$parent.$refs.editor : this.$parent.$refs['editor-code'];

            this.editor = CodeMirror.fromTextArea($el, _.extend({
                mode: 'htmlmixed',
                dragDrop: false,
                autoCloseTags: true,
                matchTags: true,
                autoCloseBrackets: true,
                matchBrackets: true,
                indentUnit: 4,
                indentWithTabs: false,
                tabSize: 4,
                lineNumbers: true,
                lineWrapping: true,
                extraKeys: {
                    F11(cm) {
                        cm.setOption('fullScreen', !cm.getOption('fullScreen'));
                    },
                    Esc(cm) {
                        if (cm.getOption('fullScreen')) cm.setOption('fullScreen', false);
                    }
                }
            }, this.$parent.options));

            this.editor.setSize(null, this.$parent.height - 2);

            this.editor.refresh();

            if (mode !== 'split') {
                this.$parent.ready = true;
            }

            this.editor.on('change', () => {
                self.editor.save();
                trigger($el, 'input');
            });

            this.$watch('$parent.active', function (state) {
                if (mode === 'split' && Number.parseInt(state) === 1) {
                    this.editor.setSize(null, this.getHeight(this.$parent.$refs.visual) - 2);
                    this.editor.refresh();
                }
            });

            this.$watch('$parent.content', function (value) {
                if (value !== this.editor.getValue()) {
                    this.editor.setValue(value);
                    this.editor.refresh();
                }
            });

            this.observe($el, mode);

            this.$emit('ready');
        },

        getHeight(el) {
            let h = 0;

            if (css(el, 'display') !== 'none') {
                return height(el);
            }
            css(el, { position: 'absolute', visibility: 'hidden', display: 'block' });
            h = height(el);
            removeAttr(el, 'style');

            return h;
        },

        observe(el, mode) {
            const vm = this;
            const element = closest(el, '.uk-switcher>*');

            if (mode === 'split') return;
            if (!element) return;

            const observer = new MutationObserver(() => {
                if (element.style.display !== 'none') {
                    vm.editor.refresh();
                }
            });

            observer.observe(element, { attributes: true, childList: true });
        }
    }

};
