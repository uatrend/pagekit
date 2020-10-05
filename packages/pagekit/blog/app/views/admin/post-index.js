const Post = {

    name: 'post',

    el: '#post',

    mixins: [Theme.Mixins.Helper],

    data() {
        return _.merge({
            posts: false,
            config: { filter: this.$session.get('posts.filter', { order: 'date desc', limit: 10 }) },
            pages: 0,
            count: '',
            selected: [],
            canEditAll: false
        }, window.$data);
    },

    theme: {
        hideEls: ['#post > div:first-child'],
        elements() {
            const vm = this;
            return {
                addpost: {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Add Post',
                    attrs: { href: vm.$url.route('admin/blog/post/edit') },
                    class: 'uk-button uk-button-primary',
                    priority: 0
                },
                selected: {
                    scope: 'topmenu-right',
                    type: 'caption',
                    caption: () => {
                        if (!vm.selected.length) return vm.$transChoice('{0} %count% Posts|{1} %count% Post|]1,Inf[ %count% Posts', vm.count, { count: vm.count });
                        return vm.$transChoice('{1} %count% Post selected|]1,Inf[ %count% Posts selected', vm.selected.length, { count: vm.selected.length });
                    },
                    class: 'uk-text-small',
                    priority: 1
                },
                search: {
                    scope: 'navbar-right',
                    type: 'search',
                    class: 'uk-text-small',
                    domProps: { value: () => vm.config.filter.search || '' },
                    on: {
                        input(e) {
                            !vm.config.filter.search && vm.$set(vm.config.filter, 'search', '');
                            vm.config.filter.search = e.target.value;
                        }
                    }
                },
                actions: {
                    scope: 'topmenu-left',
                    type: 'dropdown',
                    caption: 'Actions',
                    class: 'uk-button uk-button-text',
                    icon: { attrs: { 'uk-icon': 'triangle-down' } },
                    dropdown: { options: () => 'mode:click' },
                    actionIcons: true,
                    items: () => ({
                        publish: { on: { click: () => vm.status(2) } },
                        unpublish: { on: { click: () => vm.status(3) } },
                        copy: { on: { click: (e) => vm.copy(e) } },
                        remove: {
                            on: { click: (e) => vm.remove(e) },
                            directives: [
                                {
                                    name: 'confirm',
                                    value: 'Delete post(s)?'
                                }
                            ]
                        }
                    }),
                    priority: 2,
                    disabled: () => !vm.selected.length
                },
                pagination: {
                    scope: 'topmenu-right',
                    type: 'pagination',
                    caption: 'Pages',
                    props: {
                        value: () => vm.config.page,
                        pages: () => vm.pages,
                        name: () => vm.$options.name,
                        options: () => ({
                            lblPrev: '<span uk-pagination-previous></span>',
                            lblNext: '<span uk-pagination-next></span>',
                            displayedPages: 3,
                            edges: 1
                        })
                    },
                    on: {
                        input: (e) => {
                            if (typeof e === 'number') {
                                vm.config.page = e;
                            }
                        }
                    },
                    watch: () => vm.posts,
                    vif: () => (vm.pages > 1 || vm.config.page > 0),
                    priority: 0
                }
            };
        }
    },

    mounted() {
        this.resource = this.$resource('api/blog/post{/id}');
        this.$watch('config.page', this.load, { immediate: true });
    },

    watch: {

        'config.filter': {
            handler(filter) {
                if (this.config.page) {
                    this.config.page = 0;
                } else {
                    this.load();
                }

                this.$session.set('posts.filter', filter);
            },
            deep: true
        }

    },

    computed: {

        statusOptions() {
            const options = _.map(this.$data.statuses, (status, id) => ({ text: status, value: id }));

            return [{ label: this.$trans('Filter by'), options }];
        },

        users() {
            const options = _.map(this.$data.authors, (author) => ({ text: author.username, value: author.user_id }));

            return [{ label: this.$trans('Filter by'), options }];
        }
    },

    methods: {

        active(post) {
            return this.selected.indexOf(post.id) !== -1;
        },

        save(post) {
            this.resource.save({ id: post.id }, { post }).then(function () {
                this.load();
                this.$notify('Post saved.');
            });
        },

        status(status) {
            const posts = this.getSelected();

            posts.forEach((post) => {
                post.status = status;
            });

            this.resource.save({ id: 'bulk' }, { posts }).then(function () {
                this.load();
                this.$notify('Posts saved.');
            });
        },

        remove() {
            this.resource.delete({ id: 'bulk' }, { ids: this.selected }).then(function () {
                this.load();
                this.$notify('Posts deleted.');
            });
        },

        toggleStatus(post) {
            post.status = post.status === 2 ? 3 : 2;
            this.save(post);
        },

        copy() {
            if (!this.selected.length) {
                return;
            }

            this.resource.save({ id: 'copy' }, { ids: this.selected }).then(function () {
                this.load();
                this.$notify('Posts copied.');
            });
        },

        load() {
            this.resource.query({ filter: this.config.filter, page: this.config.page }).then(function (res) {
                const { data } = res;

                this.$set(this, 'posts', data.posts);
                this.$set(this, 'pages', data.pages);
                this.$set(this, 'count', data.count);
                this.$set(this, 'selected', []);
            });
        },

        getSelected() {
            return this.posts.filter(function (post) { return this.selected.indexOf(post.id) !== -1; }, this);
        },

        getStatusText(post) {
            return this.statuses[post.status];
        }

    }

};

export default Post;

Vue.ready(Post);
