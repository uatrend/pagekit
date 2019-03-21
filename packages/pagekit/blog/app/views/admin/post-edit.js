window.Post = {

    name: 'post',

    el: '#post',

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

    created() {
        const sections = [];

        _.forIn(this.$options.components, (component, name) => {
            // var options = component.options || {};

            // if (options.section) {
            if (component.section) {
                sections.push(_.extend({ name, priority: 0 }, component.section));
            }
        });

        this.$set(this, 'sections', _.sortBy(sections, 'priority'));

        this.resource = this.$resource('api/blog/post{/id}');
    },

    mounted() {
        const vm = this;
        // this.tab = UIkit.tab(this.$els.tab, {connect: this.$els.content});
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

        submit() {
            const vm = this;
            this.$validator.validateAll().then((res) => {
                if (res) {
                    vm.processing = true;
                    vm.save();
                }
            });
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

        settings: require('../../components/post-settings.vue').default,

    },

};

Vue.ready(window.Post);
