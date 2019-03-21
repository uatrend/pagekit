window.User = {

    name: 'user-edit',

    el: '#user-edit',

    data() {
        return _.extend({ sections: [], form: {}, processing: false }, window.$data);
    },

    created() {
        const sections = [];

        _.forIn(this.$options.components, (component, name) => {
            if (component.section) {
                sections.push(_.extend({ name, priority: 0 }, component.section));
            }
        });

        this.$set(this, 'sections', _.sortBy(sections, 'priority'));
    },

    mounted() {
        this.tab = UIkit.tab(this.$refs.tab, { connect: '#user-content' });
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
            const data = { user: this.user }; const
                vm = this;

            this.$trigger('save:user', data);

            this.$resource('api/user{/id}').save({ id: this.user.id }, data).then(function (res) {
                if (!this.user.id) {
                    window.history.replaceState({}, '', this.$url.route('admin/user/edit', { id: res.data.user.id }));
                }

                this.$set(this, 'user', res.data.user);

                this.$notify('User saved.');
                setTimeout(() => {
                    vm.processing = false;
                }, 500);
            }, function (res) {
                this.$notify(res.data, 'danger');
            });
        },

    },

    components: {

        settings: require('../../components/user-settings.vue').default,

    },

};

Vue.ready(window.User);
