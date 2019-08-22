module.exports = {

    name: 'user-index',

    el: '#users',

    mixins: [Theme.Mixins.Helper],

    data() {
        return _.merge({
            users: false,
            config: {
                filter: this.$session.get('user.filter', { order: 'username asc' }),
            },
            pages: 0,
            count: '',
            selected: [],
        }, window.$data);
    },

    theme: {
        hiddenHtmlElements: ['#users > div:first-child'],
        elements() {
            var vm = this;
            return {
                'adduser': {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Add User',
                    class: 'uk-button uk-button-primary',
                    attrs: {
                        href: () => vm.$url.route('admin/user/edit')
                    },
                    priority: 0,
                },
                search: {
                    scope: 'navbar-right',
                    type: 'search',
                    class: 'uk-text-small',
                    domProps: {
                        value: () => vm.config.filter.search || ''
                    },
                    on: {
                        input: function(e) {
                            !vm.config.filter.search && vm.$set(vm.config.filter, 'search', '');
                            vm.config.filter.search = e.target.value
                        }
                    }
                },
                'actions': {
                    scope: 'topmenu-left',
                    type: 'dropdown',
                    caption: 'Actions',
                    class: 'uk-button uk-button-text',
                    icon: {
                        attrs:{ 'uk-icon': 'triangle-down' },
                    },
                    dropdown: { options: () => 'mode:click' },
                    actionIcons: true,
                    items:() => {
                        return {
                            publish: {
                                caption: 'Activate',
                                on: {click: () => vm.status(1)},
                            },
                            unpublish: {
                                caption: 'Block',
                                on: { click: () => vm.status(0)}
                            },
                            delete: {
                                on: {click: (e) => vm.remove(e)},
                            }
                        }
                    },
                    priority: 2,
                    // vif: () => vm.selected.length,
                    disabled: () => !vm.selected.length,
                },
                'selected': {
                    scope: 'topmenu-right',
                    type: 'caption',
                    caption: () => {
                        if (!vm.selected.length)
                            return vm.$transChoice('{0} %count% Users|{1} %count% User|]1,Inf[ %count% Users', vm.count, {count: vm.count});
                        return vm.$transChoice('{1} %count% User selected|]1,Inf[ %count% Users selected', vm.selected.length, {count:vm.selected.length})
                    },
                    class: 'uk-text-small',
                    priority: 1
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
                            edges: 1,
                        })
                    },
                    on: {
                        input: (e) => {
                            if (typeof e === 'number') {
                                vm.config.page = e;
                            }
                        }
                    },
                    watch: () => vm.users,
                    vif: () => (vm.pages > 1 || vm.config.page > 0),
                    priority: 0,
                }
            }
        }
    },

    mounted() {
        this.resource = this.$resource('api/user{/id}');
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

                this.$session.set('user.filter', filter);
            },
            deep: true,
        },

    },

    computed: {

        statuses() {
            const options = [{ text: this.$trans('New'), value: 'new' }].concat(_.map(this.config.statuses, (status, id) => ({ text: status, value: id })));

            return [{ label: this.$trans('Filter by'), options }];
        },

        roles() {
            const options = this.config.roles.map(role => ({ text: role.name, value: role.id }));

            return [{ label: this.$trans('Filter by'), options }];
        },

    },

    methods: {

        active(user) {
            return this.selected.indexOf(user.id) != -1;
        },

        save(user) {
            this.resource.save({ id: user.id }, { user }).then(function () {
                this.load();
                this.$notify('User saved.');
            }, function (res) {
                this.load();
                this.$notify(res.data, 'danger');
            });
        },

        status(status) {
            const users = this.getSelected();

            users.forEach((user) => {
                user.status = status;
            });

            this.resource.save({ id: 'bulk' }, { users }).then(function () {
                this.load();
                this.$notify('Users saved.');
            }, function (res) {
                this.load();
                this.$notify(res.data, 'danger');
            });
        },

        remove() {
            this.resource.delete({ id: 'bulk' }, { ids: this.selected }).then(function () {
                this.load();
                this.$notify('Users deleted.');
            }, function (res) {
                this.load();
                this.$notify(res.data, 'danger');
            });
        },

        toggleStatus(user) {
            user.status = user.status ? 0 : 1;
            this.save(user);
        },

        showVerified(user) {
            return this.config.emailVerification && user.data.verified;
        },

        showRoles(user) {
            const vm = this;
            return _.reduce(user.roles, (roles, id) => {
                const role = _.find(vm.config.roles, { id });
                if (id !== 2 && role) {
                    roles.push(role.name);
                }
                return roles;
            }, [], this).join(', ');
        },

        load() {
            this.resource.query({ filter: this.config.filter, page: this.config.page }).then(function (res) {
                const { data } = res;

                this.$set(this, 'users', data.users);
                this.$set(this, 'pages', data.pages);
                this.$set(this, 'count', data.count);
                this.$set(this, 'selected', []);
            }, function () {
                this.$notify('Loading failed.', 'danger');
            });
        },

        getSelected() {
            return this.users.filter(function (user) {
                return this.selected.indexOf(user.id) !== -1;
            }, this);
        },

    },

    // events: {
    //     'select:pagination': function (e, page) {
    //         this.config.page = Number(page);
    //     },
    // },

};

Vue.ready(module.exports);
