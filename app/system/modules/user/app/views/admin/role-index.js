const { util } = UIkit;

module.exports = {

    name: 'user-roles',

    el: '#roles',

    mixins: [
        require('../../lib/permissions'),
        Vue2Filters.mixin,
    ],

    data: {
        role: {},
        config: window.$config,
    },

    mounted() {
        const vm = this;
        const sortable = UIkit.sortable('#roles .uk-nav', { handle: 'pk-sortable-dragged-list' });

        util.on(sortable.$el, 'added moved', this.reorder);
    },

    computed: {

        current() {
            return _.find(this.roles, { id: this.config.role }) || this.roles[0];
        },

    },

    methods: {
        roleOrdered() {
            return _.extend({}, _.orderBy(this.roles, 'priority'));
        },

        edit(role) {
            this.$set(this, 'role', _.extend({}, role || {}));
            this.$refs.modal.open();
        },

        save() {
            if (!this.role) {
                return;
            }

            this.$validator.validateAll().then((res) => {
                if (res) {
                    this.Roles.save({ id: this.role.id }, { role: this.role }).then(function (res) {
                        const { data } = res;

                        if (this.role.id) {
                            const role = _.findIndex(this.roles, 'id', this.role.id);
                            this.roles.splice(role, 1, data.role);

                            this.$notify('Role saved');
                        } else {
                            this.roles.push(data.role);
                            this.$notify('Role added');
                        }
                    }, function (res) {
                        this.$notify(res.data, 'danger');
                    });

                    this.$refs.modal.close();
                }
            });
        },

        remove(role) {
            this.Roles.remove({ id: role.id }).then(function (res) {
                this.roles.splice(_.findIndex(this.roles, { id: role.id }), 1);
            });
        },

        reorder(e, sortable, element) {
            const vm = this;

            if (!sortable) {
                return;
            }

            e.stopPropagation();

            sortable.$el.childNodes.forEach((el, i) => {
                const index = vm.roles.findIndex(role => role.id == util.data(el, 'id'));
                vm.roles[index].priority = i;
            });

            this.Roles.save({ id: 'bulk' }, { roles: this.roles }).then(function (res) {
                this.$notify('Roles reordered.');
            }, function (data) {
                this.$notify(data, 'danger');
            });
        },

    },

};

Vue.ready(module.exports);
