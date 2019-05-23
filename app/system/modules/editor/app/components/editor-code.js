var util         = UIkit.util,
    $            = util.$,
    trigger      = util.trigger,
    closest      = util.closest;

module.exports = {

    name: 'editor-code',

    created: function () {

        var baseURL = $editor.root_url + '/app/assets/codemirror';

        this.$asset({
            css: [
                baseURL + '/show-hint.css',
                baseURL + '/codemirror.css'
            ],
            js: [
                baseURL + '/codemirror.min.js'
            ]
        }).then(this.init);
    },

    methods: {
        init: function() {

            var self = this,
                mode = this.$parent.mode,
                $el  = (mode != 'combine') ? this.$parent.$refs['editor'] : this.$parent.$refs['editor-code'];

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
                lineWrapping: false,
                extraKeys: {
                    "F11": function(cm) {
                        cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                    },
                    "Esc": function(cm) {
                        if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                    }
                }
            }, this.$parent.options));

            this.editor.setSize(null, this.$parent.height);

            this.editor.refresh();

            this.editor.on('change', function () {
                self.editor.save();
                trigger($el, 'input');
            });

            this.$watch('$parent.active', function (state) {
                if (mode == 'combine' && state == 1) {
                    this.editor.setSize(null, this.getHiddenHeight(this.$parent.$refs.tinymce));
                    this.editor.refresh();
                }
            });

            this.$watch('$parent.content', function (value) {
                if (value != this.editor.getValue()) {
                    this.editor.setValue(value);
                    this.editor.refresh();
                }
            });

            this.refreshEditor($el, mode);

            this.$emit('ready');
        },

        getHiddenHeight: function(el) {
            var height = 0;

            if(util.css(el, 'display') !== 'none') {
                return util.height(el);
            }
            util.css(el, {'position': 'absolute', 'visibility': 'hidden','display': 'block'});
            height = util.height(el);
            util.removeAttr(el,'style');

            return height;
        },

        refreshEditor: function(el, mode) {
            var vm =this;

            if (mode != 'combine') {
                var observer, node = closest(el, 'li');

                if (node) {
                    observer = new MutationObserver(function(){
                       if(node.style.display !='none' ){
                            vm.editor.refresh();
                       }
                    });

                    observer.observe(node,  { attributes: true, childList: true });
                }
            }
        }
    }

};
