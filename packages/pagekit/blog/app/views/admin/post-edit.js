import { ValidationObserver, VInput } from 'SystemApp/components/validation.vue';
import settings from '../../components/post-settings.vue';

window.Post = {

    name: 'post',

    el: '#post',

    mixins: [Theme.Mixins.Helper],

    provide: {
        '$components': {
            'v-input': VInput
        }
    },

    data() {
        return {
            data: window.$data,
            post: _.merge({
                data: {
                    meta: {
                        'og:title': '',
                        'og:description': '',
                    },
                },
            }, window.$data.post),
            sections: [],
            active: this.$session.get('blog.tab.active', 0),
            form: {},
            processing: false,
        };
    },

    theme: {
        hiddenHtmlElements: ['#post > div:first-child'],
        elements() {
            var vm = this;
            return {
                'title': {
                    scope: 'breadcrumbs',
                    type: 'caption',
                    caption: () => {
                        let trans = this.$options.filters.trans;
                        return vm.post.id && trans ? trans('Edit Post') : trans('Add Post');
                    }
                },
                'savepost': {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Save',
                    class: 'uk-button tm-button-success',
                    spinner: () => vm.processing,
                    on: {click: () => vm.submit()},
                    priority: 1,
                },
                'close': {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: vm.post.id ? 'Close' : 'Cancel',
                    class: 'uk-button uk-button-text',
                    attrs: {
                        href: () => vm.$url.route('admin/blog/post')
                    },
                    disabled: () => vm.processing,
                    priority: 0,
                }
            }
        }
    },

    created() {
        const sections = [];

        _.forIn(this.$options.components, (component, name) => {

            if (component.section) {
                sections.push(_.extend({ name, priority: 0 }, component.section));
            }
        });

        this.$set(this, 'sections', _.sortBy(sections, 'priority'));

        this.resource = this.$resource('api/blog/post{/id}');
    },

    mounted() {
        const vm = this;
        this.tab = UIkit.tab('#post-tab', { connect: '#post-content' });

        UIkit.util.on(this.tab.connects, 'show', (e, tab) => {
            if (tab != vm.tab) return;
            for (const index in tab.toggles) {
                if (tab.toggles[index].classList.contains('uk-active')) {
                    vm.$session.set('blog.tab.active', index);
                    vm.active = index;
                    break;
                }
            }
        });

        this.tab.show(this.active);
    },

    methods: {

        async submit() {
            const isValid = await this.$refs.observer.validate();
            if (isValid) {
                this.processing = true;
                this.save();
            }
        },

        save() {
            const vm = this;
            const data = { post: this.post, id: this.post.id };

            // this.$broadcast('save', data);
            this.$trigger('save:post', data);

            this.resource.save({ id: this.post.id }, data).then(function (res) {
                const { data } = res;

                if (!this.post.id) {
                    window.history.replaceState({}, '', this.$url.route('admin/blog/post/edit', { id: data.post.id }));
                }

                this.$set(this, 'post', data.post);

                this.$notify('Post saved.');
                setTimeout(() => {
                    vm.processing = false;
                }, 500);
            }, function (res) {
                this.processing = false;
                this.$notify(res.data, 'danger');
            });
        },

    },

    components: {

        settings: settings,
        ValidationObserver

    },

};

Vue.ready(window.Post);
