module.exports = {

    el: '#user-profile',

    data() {
        return _.merge({
            user: { password: {} },
            hidePassword: true,
            changePassword: false,
        }, window.$data);
    },

    methods: {

        submit() {
            const vm = this;
            this.$validator.validateAll().then((res) => {
                if (res) {
                    vm.save();
                }
            });
        },

        save() {
            this.$http.post('user/profile/save', { user: this.user }).then(function () {
                this.$notify(this.$trans('Profile Updated'), 'success');
            }, function (res) {
                this.$notify(res.data, 'danger');
            });
        },

    },

};

Vue.ready(module.exports);
