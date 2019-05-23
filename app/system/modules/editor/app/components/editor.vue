<template>

    <div :class="['pk-editor', mode]">
        <template v-if="mode == 'combine'">
            <ul ref="tab" class="uk-subnav uk-flex-right" uk-switcher>
                <li><a href="">{{'Visual' | trans}}</a></li>
                <li><a href="">{{'Code' | trans }}</a></li>
            </ul>
            <ul class="uk-switcher">
                <li ref="tinymce" class="uk-invisible">
                    <textarea autocomplete="off" :style="{height: height + 'px'}" :class="{'uk-invisible': !show}" ref="editor" v-model="content"></textarea>
                </li>
                <li>
                    <textarea autocomplete="off" :style="{height: height + 'px'}" :class="{'uk-invisible': !show}" ref="editor-code" v-model="content"></textarea>
                </li>
            </ul>
        </template>
        <template v-else>
            <textarea autocomplete="off" :style="{height: height + 'px'}" :class="{'uk-invisible': !show}" ref="editor" v-model="content"></textarea>
        </template>
    </div>

</template>

<script>
    var util = UIkit.util;

    module.exports = {

        props: ['type', 'value', 'options', 'mode'],

        data: function () {
            return {
                editor: {},
                height: 500,
                active: 0,
                ready: false,
                full: false,
                show: false,
                // TODO
                content: this.value
            }
        },

        watch: {
            value: function(content) {
                this.$set(this, 'content', content);
            },

            content: function(content) {
                this.$emit('input', content);
                this.$emit('update:editor', content);
            }
        },

        created: function () {
            this.$on('hook:mounted', this.init);
        },

        mounted: function() {
            var vm = this;

            if (this.mode == 'combine') {
                this.tab = UIkit.switcher(this.$refs.tab);

                UIkit.util.on(this.tab.connects,'show', function (e, tab) {
                    if (tab != vm.tab) return false;
                    for (var index in tab.toggles) {
                        if (util.closest(util.$(tab.toggles[index]), 'li').classList.contains('uk-active')) {
                            vm.active = index;
                            break;
                        }
                    }
                });

                this.tab.show(this.active);
            }
        },

        methods: {

            init: function() {
                if (this.options && this.options.height) {
                    this.height = this.options.height
                }

                if (this.$el.hasAttributes()) {

                    var attrs = this.$el.attributes;

                    for (var i = attrs.length - 1; i >= 0; i--) {
                        if (attrs[i].name != 'class') {
                            this.$refs.editor.setAttribute(attrs[i].name, attrs[i].value);
                            this.$el.removeAttribute(attrs[i].name);
                        }
                    }

                }

                var components = this.$options.components, type = 'editor-' + this.type, self = this,
                    EditorComponent = components[type] || components['editor-' + window.$pagekit.editor] || components['editor-textarea'];

                var Editor = Vue.extend(EditorComponent);

                new Editor({parent: this}).$on('ready', function () {

                    _.forIn(self.$options.components, function (Component) {
                        if (Component.plugin) {
                            var Plugin = Vue.extend(Component);
                            new Plugin({parent: self});
                        }
                    }, this);

                    if (self.mode == 'combine') {
                        self.addCode();
                    }

                    self.ready = true;

                });
            },

            addCode: function() {

                var vm = this,
                    CodeEditor = Vue.extend(this.$options.components['editor-code']);

                new CodeEditor({parent: this});
            }
        },

        components: {

            'editor-textarea': {

                created: function () {
                    this.$emit('ready');
                    this.$set(this.$parent, 'show', true);
                }

            },
            'editor-html': require('./editor-tinymce5'),
            'editor-code': require('./editor-code'),
            'plugin-link': require('./link'),
            'plugin-image': require('./image'),
            'plugin-video': require('./video'),
            // 'plugin-url': require('./url')

        },

        utils: {
            'image-picker': Vue.extend(require('./image-picker.vue').default),
            'video-picker': Vue.extend(require('./video-picker.vue').default),
            'link-picker': Vue.extend(require('./link-picker.vue').default)
        }

    };

    Vue.component('v-editor', function (resolve) {
        resolve(module.exports);
    });

</script>
