import { on, closest, trigger } from 'uikit-util';

export default {

    name: 'editor-html',

    created() {
        const vm = this;
        const baseURL = $editor.root_url;

        this.$parent.$set(this.$parent, 'height', this.$parent.height + 31);

        this.$asset({

            css: [
                `${baseURL}/app/assets/codemirror/show-hint.css`,
                `${baseURL}/app/assets/codemirror/codemirror.css`
            ],
            js: [
                `${baseURL}/app/assets/codemirror/codemirror.min.js`,
                `${baseURL}/app/assets/marked/marked.min.js`
            ]

        }).then(function () {
            const $el = this.$parent.$refs.editor;
            const editor = this.$parent.editor = UIkit.htmleditor(this.$parent.$refs.editor, _.extend({ // eslint-disable-line no-multi-assign
                lblPreview: this.$trans('Preview'),
                lblCodeview: this.$trans('Code'),
                lblMarkedview: this.$trans('Markdown'),
                marked: window.marked,
                CodeMirror: window.CodeMirror
            }, this.$parent.options));

            on(editor.$el, 'htmleditor-save', (e, edtr) => {
                if (edtr.element && edtr.element[0].form) {
                    const event = document.createEvent('HTMLEvents');
                    event.initEvent('submit', true, true);
                    edtr.element[0].form.dispatchEvent(event);
                }
            });

            on(editor.$el, 'init', () => {
                vm.$parent.ready = true;
            });

            on(editor.$el, 'render', () => {
                const regexp = /<script(.*)>[^<]+<\/script>|<style(.*)>[^<]+<\/style>/gi;
                editor.replaceInPreview(regexp, '');
            });

            this.$watch('$parent.value', (value) => {
                if (editor.editor && (value !== editor.editor.getValue())) {
                    editor.editor.setValue(value);
                }
            });

            this.$watch('$parent.options.markdown', (markdown) => {
                trigger(editor.$el, markdown ? 'enableMarkdown' : 'disableMarkdown');
            }, { immediate: true });

            this.observe($el);

            this.$emit('ready');
        });
    },

    methods: {
        observe(el) {
            const vm = this;
            const element = closest(el, '.uk-switcher>*');

            if (!element) return;

            const observer = new MutationObserver(() => {
                if (element.style.display !== 'none') {
                    vm.$parent.editor.editor.refresh();
                }
            });

            observer.observe(element, { attributes: true, childList: true });
        }
    }

};
