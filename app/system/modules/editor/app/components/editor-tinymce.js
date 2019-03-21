var util         = UIkit.util,
    $            = util.$,
    on           = util.on,
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
            baseURL = $editor.root_url + '/app/assets/tinymce',
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

                'skin_url': $editor.root_url + '/app/assets/tinymce_skin',

                height: this.$parent.height - 87,

                mode: "exact",

                menubar: false,

                branding: false,
                // wordcount
                plugins: [
                    vm.plugins,
                    'autolink lists charmap hr anchor media',
                    'visualblocks fullscreen',
                    'paste contextmenu',
                    'textcolor colorpicker'
                    // 'advlist autolink lists charmap print preview hr anchor media',
                    // 'searchreplace visualblocks visualchars code fullscreen',
                    // 'insertdatetime nonbreaking save table contextmenu directionality',
                    // 'paste textcolor colorpicker textpattern imagetools'
                ],

                toolbar: [
                    'bold italic strikethrough bullist numlist blockquote hr alignleft aligncenter alignright link image media toggletoolbar fsbutton',
                    'formatselect underline alignjustify strikethrough charmap | forecolor removeformat | outdent indent pastetext visualblocks | textPicker widgetPicker | undo redo'
                ],

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
                        if (vm.toolbar) {
                            var icon = $('.mce-ico.mce-i-wp_adv', editorNode);
                            addClass(closest(icon, '.mce-btn'), 'mce-active');
                            addClass($('.mce-toolbar-grp .mce-toolbar.mce-last', editorNode), 'uk-display-block');
                        }
                        UIkit.util.removeClass(vm.$parent.$refs.tinymce, 'uk-invisible');
                    });
                    editor.addButton('fsbutton', {
                        icon: 'fullscreen',
                        classes: 'wp-dfw',
                        tooltip: 'Fullscreen',
                        onclick: function() {
                            editor.execCommand('mceFullScreen')
                        }
                    });
                    editor.addButton('toggletoolbar', {
                        icon: 'wp_adv',
                        tooltip: 'Toggle Toolbar',
                        onclick: function toggletoolbar(e) {
                            var parent = e.target.closest('.mce-btn'),
                                toolbar = e.target.closest('.mce-toolbar-grp');

                            if (!hasClass(parent, "mce-active")) {
                                addClass(parent, "mce-active");
                                vm.$session.set(session_param, 1);
                                addClass(find('.mce-toolbar.mce-last', toolbar), 'uk-display-block');
                            } else {
                                removeClass(parent ,"mce-active");
                                vm.$session.set(session_param, 0)
                                removeClass(find('.mce-toolbar.mce-last', toolbar), 'uk-display-block');
                            }
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