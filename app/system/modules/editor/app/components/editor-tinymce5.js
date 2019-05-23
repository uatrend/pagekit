var util         = UIkit.util,
    $            = util.$,
    $$           = util.$$,
    on           = util.on,
    css          = util.css,
    attr         = util.attr,
    removeAttr   = util.removeAttr,
    addClass     = util.addClass,
    removeClass  = util.removeClass,
    hasClass     = util.hasClass,
    find         = util.find,
    findAll      = util.findAll,
    closest      = util.closest;

module.exports = {

    name: "editor-tinymce",

    data: function () {
        return {
            plugins: [],
            toolbar: ''
        };
    },

    created: function () {
        var vm = this,
            baseURL = $editor.root_url + '/app/assets/tinymce5',
            session_param = 'editor.tinymce.toolbar',
            model = _.get(this.$parent, '$vnode.data.model.expression'),
            editorNode = this.$parent.$refs.editor.parentNode || document;

        session_param = !model ? session_param: session_param + '.' + model;
        this.toolbar = this.$session.get(session_param, 0);

        this.$parent.editor = this;

        this.$asset({
            js: [baseURL + '/tinymce.min.js']
        }).then(function () {

            this.$emit('ready');

            tinyMCE.baseURL = baseURL;
            tinyMCE.suffix = '.min';

            this.$parent.editor = tinyMCE.init(_.merge({

                skin_url: $editor.root_url + '/app/assets/tinymce5_skin',

                height: this.$parent.height,

                mode: "exact",

                menubar: false,

                branding: false,

                plugins: [
                    vm.plugins,
                    'autolink lists charmap hr anchor media',
                    'visualblocks fullscreen',
                    'paste help',
                ],

                toolbar: [
                    'formatselect bold italic bullist numlist blockquote alignleft aligncenter alignright link image media toggletoolbar | fullscreen | UNDO',
                    'underline alignjustify strikethrough hr charmap forecolor backcolor removeformat outdent indent pastetext visualblocks textPicker widgetPicker undo redo help'
                ],

                fontsize_formats: '10px 11px 12px 14px 16px 18px 24px 36px 48px',

                toolbar_item_size: "small",

                forced_root_block : "",

                force_br_newlines: true,

                force_p_newlines: "",

                document_base_url: Vue.url.options.root + '/',

                elements: [this.$parent.$refs.editor],

                element_format: 'html',

                entity_encoding: "raw",

                verify_html : false,

                setup: function(editor) {
                    editor.on('init', function () {
                        vm.$nextTick(function(){
                            var sep = $('.tox-tinymce .tox-toolbar > .tox-toolbar__group:nth-child(3)', editor.editorContainer);
                            var fs  = $('.tox-tinymce .tox-toolbar > .tox-toolbar__group:nth-child(2)', editor.editorContainer);
                            UIkit.util.css(sep, {
                                height: 0,
                                padding: 0,
                                overflow: 'hidden',
                                width: '100vw'
                            });
                            UIkit.util.css(fs, {
                                position: 'absolute',
                                right: 0,
                                border: 'none',
                                background: '#fff',
                                zIndex: 1
                            });
                            UIkit.util.css($('.tox-tbtn', fs), {
                                width: $('.tox-tbtn', fs).offsetWidth-1,
                                height: $('.tox-tbtn', fs).offsetHeight-1
                            });
                            removeClass(vm.$parent.$refs.tinymce, 'uk-invisible');
                        })
                    });
                    editor.ui.registry.addIcon('more', '<svg aria-hidden="true" role="img" focusable="false" class="dashicon dashicons-ellipsis" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path d="M5 10c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm12-2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-7 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>');
                    editor.ui.registry.addIcon('kitchensink','<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M19 2v6H1V2h18zm-1 5V3H2v4h16zM5 4v2H3V4h2zm3 0v2H6V4h2zm3 0v2H9V4h2zm3 0v2h-2V4h2zm3 0v2h-2V4h2zm2 5v9H1V9h18zm-1 8v-7H2v7h16zM5 11v2H3v-2h2zm3 0v2H6v-2h2zm3 0v2H9v-2h2zm6 0v2h-5v-2h5zm-6 3v2H3v-2h8zm3 0v2h-2v-2h2zm3 0v2h-2v-2h2z"/></g></svg>');
                    editor.ui.registry.addToggleButton('toggletoolbar', {
                        icon: 'kitchensink',
                        onAction: function (api) {
                            if (!api.isActive()) {
                                removeAttr($$('.tox-tinymce .tox-toolbar > .tox-toolbar__group:nth-child(n+4)', editor.editorContainer), 'hidden');
                                vm.$session.set(session_param, 1);
                            } else {
                                vm.$session.set(session_param, 0);
                                attr($$('.tox-tinymce .tox-toolbar > .tox-toolbar__group:nth-child(n+4)', editor.editorContainer), 'hidden', '');
                            }
                            api.setActive(!api.isActive());
                        },
                        onSetup: function (api) {
                            vm.$nextTick(function(){
                                if (!vm.toolbar) {
                                    attr($$('.tox-tinymce .tox-toolbar > .tox-toolbar__group:nth-child(n+4)', editor.editorContainer), 'hidden', '');
                                } else {
                                    api.setActive(true);
                                }
                                css($('.tox-tinymce .tox-toolbar > .tox-toolbar__group:nth-child(1)', editor.editorContainer), 'border', 'none');
                            })
                        }
                    });
                },

                init_instance_callback: function (editor) {
                    vm.tiny = editor;

                    var update = function (value) {
                        this.tiny.setContent(value || '', {format: 'text'});
                    };

                    var unbind = vm.$watch('$parent.content', update, {immediate: true});

                    editor.on('change', function () {

                        unbind();

                        vm.$parent.content = editor.getContent();

                        unbind = vm.$watch('$parent.content', update);

                    });

                    editor.on('undo', function () {
                        editor.fire('change');
                    });

                    editor.on('redo', function () {
                        editor.fire('change');
                    });

                    editor.on('keydown', function(event) {
                        if((event.ctrlKey || event.metaKey) && event.which == 83) {
                            if (typeof vm.$root.save === "function") {
                                event.preventDefault();
                                vm.$root.save();
                                return false;
                            }
                        }
                    });

                },

                save_onsavecallback: function () {

                    if (vm.$parent.$refs.editor.form) {
                        var event = document.createEvent('HTMLEvents');
                        event.initEvent('submit', true, false);
                        vm.$parent.$refs.editor.form.dispatchEvent(event);
                    }

                }

            }, $editor));

        });

    }

};